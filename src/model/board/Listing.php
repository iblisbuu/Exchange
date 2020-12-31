<?php

class Listing
{

    public function insertListing($ls_email,$ls_project_name,$ls_project_desc,$ls_corp,$ls_token_name,
                                  $ls_token_theme,$ls_token_type,$ls_website,$ls_whitepaper,$ls_contract,$ls_sns,
                                  $ls_datetime)
    {
        $db = new db();
        $query = "INSERT INTO
                    _listing 
                SET ls_email = '{$ls_email}',
                    ls_project_name = '{$ls_project_name}',
                    ls_project_desc = '{$ls_project_desc}',
                    ls_corp = '{$ls_corp}',
                    ls_token_name = '{$ls_token_name}',
                    ls_token_theme = '{$ls_token_theme}',
                    ls_token_type = '{$ls_token_type}',
                    ls_website = '{$ls_website}',
                    ls_whitepaper = '{$ls_whitepaper}',
                    ls_contract = '{$ls_contract}',
                    ls_sns = '{$ls_sns}',
                    ls_datetime = '{$ls_datetime}'
                ";

        return $db->execute($query);

    }

}