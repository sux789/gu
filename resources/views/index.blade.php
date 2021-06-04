<!doctype html>
<html lang="zh-CN">
<head>
    <!-- 必须的 meta 标签 -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap 的 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-olOxEXxDwd20BlATUibkEnjPN3sVq2YWmYOnsMYutq7X8YcUdD6y/1I+f+ZOq/47" crossorigin="anonymous">

    <title>Hello, world!</title>
    <style>
        .card-stock-image {
            width: 500px;
            margin: 10px;
            float: left;
            border: #1a202c;
        }
    </style>
</head>
<body>
<h1>Hello, world!</h1>



<div class="container-xl ">
    <div class="row ">
        <div class="col  border-dark ">
            @foreach($list as $item)
            <div class="card border-dark card-stock-image" id="card-{{$item->symbol}}">
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
                <a href="https://finance.sina.com.cn/realstock/company/{{$item->symbol}}/nc.shtml?>" target="_blank" class="card-link">
                    <img src="http://image.sinajs.cn/newchart/daily/n/{{$item->symbol}}.gif?v={{$item->updated_at}}" class="card-img-top" alt="点击看详情">
                </a>
            </div>
            @endforeach

        </div>
    </div>
    <div style="float: none"></div>
</div>

<h5><a name=buttom></a><a href='#top'>顶部</a></h5>

<!-- JavaScript 文件是可选的。从以下两种建议中选择一个即可！ -->

<!-- 选项 1：jQuery 和 Bootstrap 集成包（集成了 Popper） -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-NU/T4JKmgovMiPaK2GP9Y+TVBQxiaiYFJB6igFtfExinKlzVruIK6XtKqxCGXwCG" crossorigin="anonymous"></script>

<!-- 选项 2：Popper 和 Bootstrap 的 JS 插件各自独立 -->
<!--
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-qtoqgtVysUOibC/YeVgpOyLJpelAT1DHvg98mYHqq8ofXEmNEjaNqOZwnMKxlXCy" crossorigin="anonymous"></script>
-->
</body>
</html>
