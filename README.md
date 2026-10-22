# teamones/holidays

节假日查询组件（teamones 维护版），基于原 `nyg/holiday` 源码整理，用于判断某天是否为法定节假日、周末、调休，以及获取农历与节气信息。

## 与原包的关系

| 项 | 原包 | 本包 |
| --- | --- | --- |
| Composer 包名 | `nyg/holiday` | `teamones/holidays` |
| PHP 约束 | `^5.6\|7.*`（无法安装到 PHP 8） | `>=7.2`（PHP 7.2 – 8.5） |
| 命名空间 | `Nyg\` | `Teamones\Holiday\` |
| 兼容别名 | — | `Nyg\Holiday`（由 `compat.php` 自动注册） |
| 数据 | `cache/` 2000–2050 年月度数据 | 保持原样 |

存量业务可以继续使用 `Nyg\Holiday`，无需改代码；新代码建议使用 `Teamones\Holiday\Holiday`。

## 安装

```bash
composer require teamones/holidays
```

本地开发可先在业务项目 `composer.json` 中增加 path 仓库：

```json
{
    "repositories": [
        { "type": "path", "url": "../../composer/holidays" }
    ],
    "require": {
        "teamones/holidays": "*"
    }
}
```

## 使用

```php
use Teamones\Holiday\Holiday;

$holiday = new Holiday();

// 某天是否节假日（TYPE_ALL：含周末与调休）
$holiday->isHoliday(strtotime('2026-10-01'));

// 区间内节假日天数
$holiday->count(strtotime('2026-10-01'), strtotime('2026-10-07'), Holiday::TYPE_HOLIDAY);

// 区间内节假日日期列表
$holiday->countDate(strtotime('2026-10-01'), strtotime('2026-10-07'));

// 当前所在节日名称
$holiday->nowHoliday(time());

// 农历与节气
$holiday->getLunar(time());
$holiday->term(time());
```

### 假期类型

| 常量 | 含义 |
| --- | --- |
| `TYPE_ALL` | 全部节假日（含周末） |
| `TYPE_WEENEND` | 普通周末（2 倍工资） |
| `TYPE_HOLIDAY` | 法定节日当天（3 倍工资） |
| `TYPE_VACATION` | 节假日及前后调休 |

## 数据说明

`cache/` 内置 2000–2050 年的月度节假日与农历数据，运行时优先读取本地缓存；缓存缺失或未来月份超过 3 天未更新时，会请求百度日历接口刷新（依赖 `ext-curl`、`ext-iconv`）。生产环境建议保持缓存目录可写，或直接使用内置缓存避免外网依赖。

## PHP 8.5 兼容

- `curl_close()` 仅在 `PHP_VERSION_ID < 80500` 时调用（PHP 8.5 起该方法废弃，CurlHandle 自动释放）；
- `curl_exec()` 失败时先判断返回值再 `iconv`，避免向 `iconv` 传入 `false`；
- `filemtime()` 调用前增加 `file_exists()` 判断，避免对不存在的缓存文件产生警告；
- 无隐式 nullable 参数、无 PHP 8.0 已移除函数、无 `#[\ReturnTypeWillChange]` 相关接口实现。

验证环境：PHP 8.5.12。

## 测试

```bash
composer install
vendor/bin/phpunit
```

## License

MIT，遵循原项目授权。
