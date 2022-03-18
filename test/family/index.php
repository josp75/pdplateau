<?php
    if(!empty($_POST)){
        define("NAME", "name");
        define("DESC", "description");
        define("COLOR", "color");
        $family = new Family();
        if(isset($_POST[NAME]) && isset($_POST[DESC] )&& isset($_POST[COLOR])){
            $family->setName(htmlspecialchars(trim($_POST['name'])));
            $family->setDescription(htmlspecialchars($_POST['description']));
            //$family->setColor(htmlspecialchars($_POST['color']));
            try {
                $family->save();
            } catch (Exception $e) {
                 Watcher::json(["status" => 0, "error" => ""]);
            }
        }
    }




