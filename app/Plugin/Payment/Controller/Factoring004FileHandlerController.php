<?php

class Factoring004FileHandlerController extends PaymentAppController
{

    public function fileUpload()
    {
        $file = $this->request->params['form']['file'];
        $filename = basename($file['name']);
        $uploadFolder = WWW_ROOT . 'files';
        $filename = time() .'_'. $filename;
        $uploadPath =  $uploadFolder . DS . $filename;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $this->response->body(json_encode(['success'=>false]));
        } else {
            $this->response->body(json_encode(['success'=>true,'filename' => $filename]));
        }

        $this->response->type('application/json');
        $this->response->send();
        $this->_stop();
    }

    public function fileRemove()
    {
        $filename = $this->request->data['filename'];

        if (!unlink( WWW_ROOT . 'files' . DS . $filename)) {
            $this->response->body(json_encode(['success'=>false]));
        }
        else {
            $this->response->body(json_encode(['success'=>true]));
        }

        $this->response->type('application/json');
        $this->response->send();
        $this->_stop();
    }
}