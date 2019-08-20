define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zhibocms/zbjk/hylist/index' + location.search,
                    add_url: 'zhibocms/zbjk/hylist/add',
                    edit_url: 'zhibocms/zbjk/hylist/edit',
                    del_url: 'zhibocms/zbjk/hylist/del',
                    multi_url: 'zhibocms/zbjk/hylist/multi',
                    table: 'hylist'
                }
            });

            var table = $("#table");

            $.fn.bootstrapTable.locales[Table.defaults.locale]['formatSearch'] = function(){return "请输入频道名称";};
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'cloud_id', title: __('Meetcode')},
                        {field: 'channel.channel_name', title: __('Title')},
                        {field: 'starttime_text', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'stoptime_text', title: __('Stoptime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate',
                            title: __('Operate'), 
                            table: table, 
                            events: Table.api.events.operate,
                            formatter: Controller.api.formatter.operate 
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                operate: function (value, row, index) {
                        var table = this.table;
                        // 操作配置
                        var options = table ? table.bootstrapTable('getOptions') : {};
                        // 默认按钮组
                        var buttons = $.extend([], this.buttons || []);
                        // 所有按钮名称
                        var names = [];
                        buttons.forEach(function (item) {
                            names.push(item.name);
                        });
//                     
//                        if (options.extend.dragsort_url !== '' && names.indexOf('dragsort') === -1) {
//                            buttons.push({
//                                name: 'dragsort',
//                                icon: 'fa fa-arrows',
//                                title: __('Drag to sort'),
//                                extend: 'data-toggle="tooltip"',
//                                classname: 'btn btn-xs btn-primary btn-dragsort'
//                            });
//                        }
//                        if (options.extend.edit_url !== '' && names.indexOf('edit') === -1) {
//                            buttons.push({
//                                name: 'edit',
//                                icon: 'fa fa-pencil',
//                                title: __('Edit'),
//                                extend: 'data-toggle="tooltip"',
//                                classname: 'btn btn-xs btn-success btn-editone',
//                                url: options.extend.edit_url
//                            });
//                        }
//                        if (options.extend.del_url !== '' && names.indexOf('del') === -1) {
//                            buttons.push({
//                                name: 'del',
//                                icon: 'fa fa-trash',
//                                title: __('Del'),
//                                extend: 'data-toggle="tooltip"',
//                                classname: 'btn btn-xs btn-danger btn-delone'
//                            });
//                        }
                        return Table.api.buttonlink(this, buttons, value, row, index, 'operate');
                    }
        }
      }
    };
    return Controller;
});