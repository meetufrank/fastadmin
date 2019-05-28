define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'meetingroom/meet/meetlist/index' + location.search,
                    add_url: 'meetingroom/meet/meetlist/add',
                    edit_url: 'meetingroom/meet/meetlist/edit',
                    del_url: 'meetingroom/meet/meetlist/del',
                    multi_url: 'meetingroom/meet/meetlist/multi',
                    table: 'meetlist',
                    control_meet_url:'meetingroom/meet/meetlist/meetControl'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'meetcode', title: __('Meetcode')},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'stoptime', title: __('Stoptime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Normal'),"hidden":__('Hidden')}, formatter: Table.api.formatter.status},
                        {field: 'weigh', title: __('Weigh')},
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
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'meetingroom/meet/meetlist/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), align: 'left'},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'meetingroom/meet/meetlist/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'meetingroom/meet/meetlist/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
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
                     
                        if (options.extend.control_meet_url !== '' && names.indexOf('meetControl') === -1) {
                            buttons.push({
                                name: 'meetControl',
                                text:__('Control'),
                                icon: 'fa fa-forward',
                                title: row.title,
                                classname: 'btn btn-xs btn-success btn-addtabs',
                                url: options.extend.control_meet_url
                            });
                        }
                        if (options.extend.dragsort_url !== '' && names.indexOf('dragsort') === -1) {
                            buttons.push({
                                name: 'dragsort',
                                icon: 'fa fa-arrows',
                                title: __('Drag to sort'),
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-xs btn-primary btn-dragsort'
                            });
                        }
                        if (options.extend.edit_url !== '' && names.indexOf('edit') === -1) {
                            buttons.push({
                                name: 'edit',
                                icon: 'fa fa-pencil',
                                title: __('Edit'),
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-xs btn-success btn-editone',
                                url: options.extend.edit_url
                            });
                        }
                        if (options.extend.del_url !== '' && names.indexOf('del') === -1) {
                            buttons.push({
                                name: 'del',
                                icon: 'fa fa-trash',
                                title: __('Del'),
                                extend: 'data-toggle="tooltip"',
                                classname: 'btn btn-xs btn-danger btn-delone'
                            });
                        }
                        return Table.api.buttonlink(this, buttons, value, row, index, 'operate');
                    }
        }
      }
    };
    return Controller;
});