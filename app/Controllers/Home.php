<?php

namespace App\Controllers;
use App\Models\UserModel;
class Home extends BaseController
{
    public function index()
    {
        $data=array(
            'js' => [base_url() . '/home.js?v=1'],
        );
        return view('welcome_message',$data);
    }

    public function get(){
        $userModel = new UserModel();
        $data = $userModel->orderBy('id', 'DESC')->findAll();

        $html = '';$no=1;

        if($data!=null){
            foreach ($data as $row){
                $html.= /** @lang text */
                '<tr>
                    <td>'.$no++.'</td>
                    <td>'.$row["name"].'</td>
                    <td>'.$row["email"].'</td>
                    <td><button class="btn btn-primary" id="edit_'.$row["id"].'" onclick="edit(\'' .$row['id'] .'\')">Edit</button> <button class="btn btn-danger" id="id'.$row["id"].'" onclick="deleted(\'' .$row['id'] .'\')">Delete</button></td>
                </tr>';
            }
        }else{
            $html.=/** @lang text */ '<tr><td colspan="4">data not found</td></tr>';
        }
        echo json_encode(array("res"=>$html));
    }

    public function create(){
        $userModel = new UserModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'email'  => $this->request->getVar('email'),
        ];
        $id=$this->request->getVar('id');
        if($id==""){
            $userModel->insert($data);
        }
        else{
            $userModel->update($id, $data);
        }
        echo json_encode(array("status"=>$userModel));
    }

    public function edit(){
        $userModel = new UserModel();
        $id=$this->request->getVar('id');
        $res = $userModel->where('id', $id)->first();
        echo json_encode(array("status"=>true,"res"=>$res));
    }

    public function delete(){
        $userModel = new UserModel();
        $id=$this->request->getVar('id');
        $result = $userModel->where(['id'=>$id])->delete();
        echo json_encode(array("status"=>$result));
    }
}
