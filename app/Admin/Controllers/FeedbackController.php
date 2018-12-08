<?php

namespace App\Admin\Controllers;

use App\Models\Feedback;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class FeedbackController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('意见反馈')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑')
            ->description('正在处理...')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Feedback);

        $grid->id('ID')->sortable();

        $grid->name('称呼');

        $grid->mobile('联系方式');

        $grid->content('反馈内容')->display(function ($content) {
            return '<span title="' . $content . '">' . str_limit($content, 30, '...') . '</span>';
        });

        $status = [
            'on' => ['value' => 1, 'text' => '已解决', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '待解决', 'color' => 'danger'],
        ];
        $grid->status('待解决/已解决')->switch($status);

        $grid->result('处理结果')->display(function ($result) {
            return str_limit($result, 20, '...');
        })->editable('textarea');

        $grid->created_at('创建时间');
        $grid->updated_at('修改时间');

        $grid->disableRowSelector();//禁用行选择checkbox
        $grid->disableCreateButton();//禁用创建按钮
        $grid->actions(function ($actions) {
            $actions->disableDelete();//禁止删除按钮
            $actions->disableView();//禁止查看
        });

        //查询过滤器
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('name', '名称');
            $filter->like('mobile', '联系方式');

            $filter->equal('status')->radio([
                '' => '所有',
                '1' => '已解决',
                '0' => '未解决'
            ]);
        });


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Feedback::findOrFail($id));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Feedback);

        $form->display('created_at', '提交时间');

        $form->display('name', '称呼');

        $form->display('mobile', '联系方式');

        $form->display('content', '反馈内容');

        $status = [
            'on' => ['value' => 1, 'text' => '已解决', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '待解决', 'color' => 'danger'],
        ];
        $form->switch('status', '是否解决')->states($status);

        $form->textarea('result', '处理结果')->rows(3)->placeholder('请填写处理结果');

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();

            // 去掉`查看`按钮
            $tools->disableView();
        });

        $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        });

        return $form;
    }
}
