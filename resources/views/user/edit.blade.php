@extends('layout.adminindex')
@section('con')
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
    	<span>修改页面</span>
    </div>
    <div class="mws-panel-body no-padding">
    	<form action="/admin/user/update" class="mws-form" method='post'>
    	   {{csrf_field()}}
           <input type="hidden" name="id" value='{{$vo['id']}}'>
    		<div class="mws-form-inline">
        		<div class="mws-form-row">
        			<label class="mws-form-label">邮箱</label>
        				<div class="mws-form-item">
        					<input type="text" class="small" name='email' value='{{$vo['email']}}'>
        				</div>
        		</div>
    		</div>

    		<div class="mws-form-inline">
        		<div class="mws-form-row">
        			<label class="mws-form-label">状态</label>
        				<div class="mws-form-item">
        					<select name="status" class='small'>
                                <option value="0"@if($vo['status']==0) selected @endif>禁用</option>               
                                <option value="1"@if($vo['status']==1) selected @endif>启用</option>               
                            </select>
        				</div>
        		</div>
    		</div>

    		<div class="mws-button-row">
    			<input type="submit" class="btn btn-danger" value="提交">
    		</div>
    	</form>
    </div>    	
</div>
</body>
</html>

@endsection