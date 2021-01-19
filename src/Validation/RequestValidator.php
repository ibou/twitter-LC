<?php


namespace Twitter\Validation;


use Twitter\Http\Request;
use Twitter\Http\Response;

class RequestValidator
{
    public function validateFields(Request $request, array $requiredFields): ?Response
    {
        $invalidFields = [];
        foreach ($requiredFields as $field) {
            if (null === $request->get($field)) {
                $invalidFields[] = $field;
            }
        }
        if (empty($invalidFields)) {
            return null;
        }
        
        if (count($invalidFields) === 1) {
            $field = $invalidFields[0];
            
            return new Response("Le champs {$field} est manquant", 400);
        }
        
        return new Response(sprintf("Les champs %s sont manquants", implode(', ', $invalidFields)), 400);
    }
}