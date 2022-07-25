<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Services\Tree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class AdminMenuController extends Controller
{
    /**
     * User: lyw@qq.com
     * Date: 2020/12/1 15:52
     * 菜单管理
     */
    public function lists(Request $request)
    {
        $cateList = AdminMenu::query()->select('id', 'title','pid','href','status','menu_status','sort')->orderBy('sort','desc')->get()->toArray();
        //使用菜单树
        Tree::instance()->init($cateList);
        $menuList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0),'title');
        return response()->json(['code'=>0,'data'=>$menuList]);
    }

    public function add()
    {
        $cateList = AdminMenu::query()->select('id', 'title','title as name', 'pid','pid as p_id','href')->orderBy('sort','desc')->get()->toArray();
        //使用菜单树
        Tree::instance()->init($cateList);
        $menuList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0),'title');
        foreach ($menuList as &$item){
            $item['title'] = str_replace('&nbsp;',' ',$item['title']);
        }
        return view('admin.menu.add', ['menuList' => $menuList]);
    }

    public function postAdd(Request $request)
    {
        $menu = new AdminMenu();
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|string|min:1',
            'pid' => 'required|integer|min:0',
//            'icon' => 'required',
        ], [
            'title.required' => '分类名称必须填写',
            'title.min' => '分类长度必须大于等于 :min',
            'pid.required' => '必须选择一个有效的上级分类',
            'pid.integer' => '必须选择一个有效的上级分类',
            'pid.min' => '必须选择一个有效的上级分类',
//            'icon.require' => '分类图标必须上传',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
        $menu->pid = $data['pid'];
        $menu->title = $data['title'];
        $menu->icon = $data['icon'] ? $data['icon'] : 0;
        $menu->sort = $data['sort'];
        $menu->menu_status = $data['menu_status'];
        $menu->href = $data['href'];
        $menu->create_time = time();
        $result = $menu->save();
        return $result ? $this->success(['msg' => '添加成功!', 'data' => $menu]) : $this->error('添加失败!');
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $menu = AdminMenu::query()->where('id',$id)->first();
        $cateList = AdminMenu::query()->select('id', 'title','title as name', 'pid','pid as p_id','href')->orderBy('sort','desc')->get()->toArray();
        //使用菜单树
        Tree::instance()->init($cateList);
        $menuList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0),'title');
        foreach ($menuList as &$item){
            $item['title'] = str_replace('&nbsp;',' ',$item['title']);
        }
        return view('admin.menu.edit', ['menuList' => $menuList, 'menu' => $menu]);
    }

    public function postEdit(Request $request)
    {
        $data = $request->all();
        $menu = AdminMenu::query()->find($data['id']);

        $validator = Validator::make($data, [
            'title' => 'required|string|min:1',
            'pid' => 'required|integer|min:0',
//            'icon' => 'required',
        ], [
            'title.required' => '分类名称必须填写',
            'title.min' => '分类长度必须大于等于 :min',
            'pid.required' => '必须选择一个有效的上级分类',
            'pid.integer' => '必须选择一个有效的上级分类',
            'pid.min' => '必须选择一个有效的上级分类',
//            'icon.require' => '分类图标必须上传',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }
//            $cate = SystemMenu::query()->where('p_id',$data['p_id'])->first();

//            if ($cate->p_id != 0) {
//                return $this->error('只能添加到二级分类');
//            }
        $menu->pid = $data['pid'];
        $menu->title = $data['title'];
        $menu->icon = $data['icon'] ? $data['icon'] : 0;
        $menu->menu_status = $data['menu_status'];
        $menu->sort = $data['sort'];
        $menu->href = $data['href'];
        $menu->update_time = time();
        $result = $menu->save();
        return $result ? $this->success(['msg' => '更新成功!', 'data' => $menu]) : $this->error('更新失败!');

    }

    public function status(Request $request)
    {
        $id   = trim($request->input('id'));
        $menu = AdminMenu::query()->find($id);

        if ($menu->status == 1) {
            $menu->status = 0;
        } else {
            $menu->status = 1;
        }
        try {
            $bool  = $menu->save();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        if ($bool) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    public function list_status(Request $request)
    {
        $id   = trim($request->input('id'));
        $menu = AdminMenu::query()->find($id);

        if ($menu->menu_status == 1) {
            $menu->menu_status = 0;
        } else {
            $menu->menu_status = 1;
        }
        try {
            $bool  = $menu->save();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        if ($bool) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    public function del(Request $request)
    {
        $id = $request->input('id');
        DB::beginTransaction();
        try {
            //遍历获取子分类并删除
            $is_exit = AdminMenu::query()->where('pid',$id)->first();
            if(!empty($is_exit)){
                DB::rollBack();
                return $this->error('该菜单下有子菜单');
            }
            AdminMenu::query()->where('id',$id)->delete();
            DB::commit();
            $result = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $result = false;
        }
        return $result ? $this->success('删除成功！') : $this->error('删除失败！');
    }

    public function sort(Request $request){
        $data = $request->all();
        $menu = AdminMenu::query()->where(['id'=>$data['id']])->first();
        $menu->sort = intval($data['sort']);
        try {
            $bool  = $menu->save();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        if ($bool) {
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    public function icon(Request $request){
        return view('admin.menu.icon');
    }

}
