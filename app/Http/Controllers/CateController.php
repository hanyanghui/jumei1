<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CateController extends Controller
{
    //分类的添加
    public function getAdd($id=''){

    	// 获取格式化类别数据
    	$cate = self::getCates();
    	return view('cate.add',['list'=>$cate,'id'=>$id]);
    }

    // 获取格式化类别数据
    public static function getCates(){
    	// select *,concat(path,id) as paths from cate order by paths
    	$cate =DB::table('cate')->select('*',DB::raw('concat(path,id) as paths'))->orderBy('paths')->get();
    	// var_dump($cate);
    	// 修改类别的样式
    	foreach($cate as $k=>$v){
    		// 分配的级别 0 顶级 1 一级  2  二级
    		$num = count(explode(',',$v['path']))-2;
    		$cate[$k]['cate'] = str_repeat('**',$num).$v['cate'];
    		// echo (count(explode(',',$v['path']))-2).'---'.$v['cate'].'<br>';

    	}
    	// var_dump($cate);
    	return $cate;
    }

   	// 执行插入
   	public function postInsert(Request $request){
   		// var_dump($request->all());
   		if($request->input('id')==0){
   			// 添加顶级类
   			$data['cate']=$request->input('cate');
   			$data['pid']=0;
   			$data['path']='0,';
   		}else{
   			// 添加某一个类下面的子类
   			$data['cate']=$request->input('cate');
   			$data['pid']=$request->input('id');//子类的pid父类的id
   			$path =DB::table('cate')->where('id',$request->input('id'))->first()['path'];
   			$data['path']=$path.$request->input('id').',';//父类的path,父类的id
   		}
   		// dd($data);
   		$res = DB::table('cate')->insert($data);
   		if($res){
   			return redirect('/admin/cate/index')->with('success','添加分类成功');
   		}else{
   			return back()->with('error','添加失败');
   		}
   	}
   	// 分类的列表
   	public function getIndex(){
   		return view('cate.index',['list'=>self::getCates()]);
   	}
   	// 根据子类的pid转化父类的类名
   	public static function funame($pid){
   		$funame =DB::table('cate')->where('id',$pid)->first()['cate'];
   		echo empty($funame)?'顶级分类':$funame;
   		// if($pid==0){
   		// 	echo '顶级分类';
   		// }else{
   		// 	echo DB::table('cate')->where('id',$pid)->first()['cate'];
   		// }
   		
   	}

   	// 删除分类
   	public function getDel($id){
   		 $data =DB::table('cate')->where('pid',$id)->get();
   		 if(count($data)>0){
   		 	// 有子类
   		 	return back()->with('error','该下面有子类不能直接删除');
   		 }else{
   		 	// 没有子类
   		 	$res =DB::table('cate')->where('id',$id)->delete();
   		 	if($res){
   		 		return redirect('/admin/cate/index')->with('success','删除成功');
   		 	}else{
   		 		return back()->with('error','删除失败');
   		 	}
   		 }
   	}

   	// 修改
   	public function getEdit($id){
   		// 根据子类id查询父类的名称
   		// 左链接 右链接 内联 自连接
   			// select c1.*,c2.cate from cate as c1 ,cate as c2 where c1.pid=c2.id and c1.id=12;
   		$funame = DB::table('cate as c1')
				->join('cate as c2','c1.pid','=','c2.id')
				->select('c2.cate as funame')
				->where('c1.id',$id)
				->first()['funame'];
		$funame =  empty($funame)?'顶级分类':$funame;
		// dd($funame);
   		return view('cate.edit',[
   			'vo'=>DB::table('cate')->where('id',$id)->first(),
   			'funame'=>$funame
   			]);
   	}

   	// 执行修改
   	public function postUpdate(Request $request){
   		// dd($request->all());
   		if(DB::table('cate')->where('id',$request->input('id'))->update($request->only('cate'))){
   			return redirect('/admin/cate/index')->with('success','修改成功');
   		}else{
   			return back()->with('error','修改失败');
   		}
   	}
}