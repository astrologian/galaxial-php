<?php

/**
 * Небольшой хелпер для работы со строками
 */
class Chars {
    /**
     * Все заглавные
     */
    public static function upper($str) {
        $new_str = mb_strtoupper($str);
        return $new_str;
    }
    /**
     * Все пробелы заменяются на дефисы
     */
    public static function hyphenate($str) {
        $new_str = mb_strtolower(str_replace(" ", "-", $str));
        return $new_str;
    }
    /**
     * Все пробелы заменяются на подчеркивания
     */
    public static function underscore($str) {
        $new_str = mb_strtolower(str_replace(" ", "_", $str));
        return $new_str;
    }
    /**
     * Все пробелы удаляются, первые буквы всех слов становятся заглавными
     */
    public static function camelcase($str) {
        $new_str = str_replace(" ", "", mb_convert_case($str, MB_CASE_TITLE, "UTF-8"));
        return $new_str;
    }
    /**
     * Кириллица -> латиница
     */
    public static function translate($str) {
        $repl = array(
            "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я",
            "а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я"
        );
        $with = array(
            "A", "B", "V", "G", "D", "E", "YO", "ZH", "Z", "I", "J", "K", "L", "M", "N", "O", "P", "R", "S", "T", "U", "F", "H", "C", "CH", "SH", "SHCH", "J", "Y", "J", "E", "YU", "YA",
            "a", "b", "v", "g", "d", "e", "yo", "zh", "z", "i", "j", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "h", "c", "ch", "sh", "shch", "j", "y", "j", "e", "yu", "ya"
        );
        $new_str = str_replace($repl, $with, $str);
        return $new_str;
    }
}
