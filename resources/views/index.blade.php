<!doctype html>
<html lang="zh-CN">
<head>
    <!-- 必须的 meta 标签 -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 的 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.1.0/css/bootstrap.min.css"/>
    <title>Hello, world!</title>
    <style>
        .card-stock-image {
            max-width: 600px;
            margin: 10px;
            float: left;
            border: #1a202c;
        }
    </style>
</head>
<body>

<div class="container-xl ">
    <div class="row ">
        <div class="col  border-dark ">
            @foreach($list as $item)
                <div class="card border border-primary card-stock-image" id="card-{{$item->symbol}}">
                    <div class="card-body">
                        <span>PE:{{$item->pe}} 涨幅{{$item->changepercent}}换手{{$item->turnoverratio}} 流通{{$item->nmc}}亿</span>
                        <button type="button" class="btn btn-primary like"
                                _data-toggle="modal"
                                _data-target="#modal-liking-edit"
                                data-code=""
                                data-title=""
                                id="btn-like-"
                        >收藏
                        </button>
                    </div>
                    <a href="https://finance.sina.com.cn/realstock/company/{{$item->symbol}}/nc.shtml?>" target="_blank"
                       class="card-link">
                        <img src="http://image.sinajs.cn/newchart/daily/n/{{$item->symbol}}.gif?v={{$item->updated_at}}"
                             class="card-img-top" alt="点击看详情">
                    </a>
                </div>
            @endforeach
            <div style="float: none"></div>
        </div>
    </div>

</div>


</body>
</html>
