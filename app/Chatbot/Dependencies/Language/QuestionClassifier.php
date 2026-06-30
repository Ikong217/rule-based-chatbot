<?php

namespace App\Chatbot\Dependencies\Language;


class QuestionClassifier
{
    protected static array $types = [
        'how'   => ['how', 'how do', 'how can'],
        'who'   => ['who','whom'],
        'what'  => ['what', 'what is'],
        'when'  => ['when'],
        'why'   => ['why'],
        'where' => ['where'],
    ];

    public static function detect(string $message): ?string
    {
        $message = strtolower($message);

        foreach(self::$types as $type => $keywords){
            foreach($keywords as $keyword){
                if(str_starts_with($message, $keyword)){
                    return $type;
                }
            }
        }

        return null;
    }
}
