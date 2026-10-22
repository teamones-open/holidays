<?php
/**
 * 兼容别名：保留原 nyg/holiday 的 Nyg\Holiday 类名，
 * 便于存量业务不改代码即可切换依赖到 teamones/holidays。
 */
if (!class_exists('Nyg\\Holiday', false)) {
    class_alias(\Teamones\Holiday\Holiday::class, 'Nyg\\Holiday');
}
