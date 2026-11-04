<?php

namespace Zayd54500\TouiteurSae\Service;

class HashtagExtractor {

    public function extraire(string $contenu): array
    {
        preg_match_all('/#([a-zA-Z0-9_]+)/u', $contenu, $matches);

        return array_values(array_unique($matches[1]));
    }
}