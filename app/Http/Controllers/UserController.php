<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //加载添加表单
    public function getAdd(){
    	return view('user.add');
    }

    // 执行添加操作
    public function postInsert(Request $request){
    	// if(!$request->input('name')){
    	// 	return back()->withInput();
    	// }
    	// 1.验证表单数据
    	$this->validate($request,[
    		'name'=>'required', //name字段必填
    		'username'=>'required|unique:user', //账号必填
    		'repass'=>'same:pass|required',
    		'email'=>'required|email'
    	],[
    		'name.required'=>'姓名必须填写',
            'username.required'=>'账号必须填写',
    		'username.unique'=>'账号已存在',
    		'repass.same'=>'两次密码不一致',
    		'repass.required'=>'重复密码必须填写',
    		'email.required'=>'邮箱必须填写',
    		'email.email'=>'邮箱格式不正确'
    	]);
    	// echo '执行数据操作';
    	// 2.数据的插入
    	$data =$request->except(['_token','repass']);

    	// 3.数据处理
    	$data['pass'] = Hash::make($data['pass']); //解密Hash::check()
    	$data['token'] = str_random(50); //邮箱注册的身份验证
    	$data['status'] = 0; //0禁用 1 启用

    	// 4.数据插入
    	$res =DB::table('user')->insert($data);
    	if($res){
    		// echo '跳转到用户浏览页面';
    		return redirect('/admin/user/index')->with('success','添加成功');
    	}else{
    		return back()->with('error','添加失败');//session(['error'=>'添加失败'])  session('error')  //withInput  做闪存
    		// 信息提示 
    		// 做闪存
    	}

    	// dd($data);
    }

    // 首页
    public function getIndex(Request $request){
        // 获取搜索所有的数据
        // $data = $request->all();
        // var_dump($data);
        // 查询数据
        // use  可以导入外面的变量到我的函数中使用 导入之后只能使用不能修改
    	$data = DB::table('user')->where(function($query) use($request){//$query  就是数据库user表的模型
            if($request->input('keyword')){
                $query->where('name','like','%'.$request->input('keyword').'%')
                    ->orwhere('email','like','%'.$request->input('keyword').'%');
            }
            
        
        })->paginate($request->input('num',5));//指定按照2条分页
    	// 分页页码
        // var_dump($data->render());
        return view('user.index',['list'=>$data,'request'=>$request->all()]);
    }

    // 删除
    public function getDel($id){
       $res =DB::table('user')->where('id','=',$id)->delete();
       if($res){
            return redirect('/admin/user/index')->with('success','删除成功');
       }else{
        return back()->with('error','删除失败');
       }
    }

    // 修改页面
    public function getEdit($id){
    	$vo =DB::table('user')->where('id',$id)->first();
    	return view('user.edit',['vo'=>$vo]);
    }

    // 执行修改
    public function postUpdate(Request $request){
        $this->validate($request,[
            'email'=>'required|email'
        ],[
            'email.required'=>'邮箱必须填写',
            'email.email'=>'邮箱格式不正确'
        ]);


    	$data = $request->only('email','status');
    	// dd($data);
        $res =DB::table('user')->where('id',$request->input('id'))->update($data);
        if($res){
            return redirect('/admin/user/index')->with('success','修改成功');
        }else{
            return back()->with('error','修改失败');
        }
    }

}
