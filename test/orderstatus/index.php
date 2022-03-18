<?php

    if($_POST){
        $orderStatus = new OrderStatus();
        if(isset($_POST['name']) && isset($_POST['description'] )&& isset($_POST['color'])){
            $orderStatus->setName($_POST['name']);
            $orderStatus->setDescription($_POST['description']);
            $orderStatus->setColor($_POST['color']);
            try {
                $orderStatus->save();
            } catch (Exception $e) {
                 Watcher::json(["status" => 0, "error" => ""]);
            }
        }
    }




