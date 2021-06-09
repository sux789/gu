<?php


namespace App\Services\Snap;


use App\Models\Snap;
use Illuminate\Support\Facades\DB;

/**
 * 股票快照
 * 拆分1 同步服务SnapSyncService,异步削峰处理
 * 拆分2 交易日服务 用统一缓存,为了减少bug
 */
class SnapService
{


}
