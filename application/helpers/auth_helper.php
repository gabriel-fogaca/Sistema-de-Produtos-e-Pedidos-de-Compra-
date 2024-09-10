<?php

function permission(){
    $ci = get_instance();
    $loggedUser = $ci->session->userdata("user_id");
    if(!$loggedUser){
        $ci->session->set_flashdata("danger", "Voce precisa estar logado como administrador para acessar esta p�gina!");
        redirect('login');
    }
    return $loggedUser;
}