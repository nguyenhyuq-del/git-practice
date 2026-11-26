<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
function md_e($v): string {
    return AdminDashboardController::membership_md_e($v);
}
function md_money($v): string {
    return AdminDashboardController::membership_md_money($v);
}
function md_get(string $k,string $default=''): string {
    return AdminDashboardController::membership_md_get($k, $default);
}
function md_date(string $v): bool {
    return AdminDashboardController::membership_md_date($v);
}
function md_query(mysqli $db,string $sql,string $types='',array $p=[]): array {
    return AdminDashboardController::membership_md_query($db, $sql, $types, $p);
}
function md_csv($v): string {
    return AdminDashboardController::membership_md_csv($v);
}
function md_url(array $changes=[]): string { global $filters; return 'membership_dashboard.php?'.http_build_query(array_merge($filters,$changes)); }
extract((new AdminDashboardController())->membership(), EXTR_OVERWRITE);
?><!DOCTYPE html>
<html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard nâng hạng | TT SHOP</title><link rel="stylesheet" href="admin.css"><link rel="stylesheet" href="membership_dashboard.css"></head><body><div class="admin-layout">
<?php $page='membership_dashboard';include __DIR__.'/sidebar.php'; ?>
<main class="main md-main">
<header class="topbar"><div><p class="eyebrow">QUẢN LÝ THÀNH VIÊN</p><h1>Dashboard nâng hạng</h1><p class="topbar-subtitle">Theo dõi khách hàng mua gói thành viên và doanh thu đã xác nhận qua PayOS.</p></div><div class="md-clock">Hôm nay: <strong><?= $today->format('d/m/Y') ?></strong><br>Thời điểm tải: <?= date('H:i:s') ?></div></header>
<?php if($error):?><div class="box md-warning"><?=md_e($error)?></div><?php endif;?>
<?php if($dbError):?><div class="box md-warning" role="alert"><?=md_e($dbError)?> <a class="detail" href="membership_dashboard.php">Thử lại</a></div><?php else:?>
<section class="box md-panel"><div class="md-quick"><?php foreach(['Hôm nay'=>$defaultTo,'7 ngày'=>$today->modify('-6 days')->format('Y-m-d'),'30 ngày'=>$today->modify('-29 days')->format('Y-m-d'),'Tháng này'=>$defaultFrom] as $label=>$f):?><a class="md-small <?= $from===$f&&$to===$defaultTo?'md-active':'' ?>" href="<?=md_e(md_url(['from'=>$f,'to'=>$defaultTo]))?>"><?=md_e($label)?></a><?php endforeach;?></div>
<form method="get" class="md-filters"><label>Từ ngày<input type="date" name="from" value="<?=md_e($from)?>" required max="<?=$defaultTo?>"></label><label>Đến ngày<input type="date" name="to" value="<?=md_e($to)?>" required max="<?=$defaultTo?>"></label><label>Trạng thái danh sách<select name="status"><?php foreach($statuses as $k=>$v):?><option value="<?=$k?>" <?=$status===$k?'selected':''?>><?=md_e($v)?></option><?php endforeach;?></select></label><label>Hạng đã mua<select name="rank"><option value="all">Tất cả hạng</option><?php for($i=1;$i<=4;$i++):?><option value="<?=$i?>" <?=$rank===(string)$i?'selected':''?>><?=$tiers[$i]?></option><?php endfor;?></select></label><label class="md-search">Tìm khách / mã giao dịch<input name="q" value="<?=md_e($q)?>" maxlength="100" placeholder="Tên, email, SĐT, mã giao dịch"></label><button type="submit">Lọc dữ liệu</button><a class="md-small" href="membership_dashboard.php">Đặt lại</a></form><p class="md-note">Kỳ xem: <?=date('d/m/Y',strtotime($from))?> – <?=date('d/m/Y',strtotime($to))?>. KPI và biểu đồ luôn tính giao dịch PAID theo ngày xác nhận, không phụ thuộc bộ lọc trạng thái danh sách.</p></section>
<section class="box md-panel md-export"><div><strong>Xác nhận thanh toán tự động</strong><p class="md-note">Chỉ ghi nhận giao dịch PAID do hệ thống xác minh. Toàn hệ thống: <?=number_format($pending)?> chờ thanh toán · <?=number_format($review)?> cần kiểm tra.</p></div><a class="btn md-outline" href="<?=md_e(md_url(['export'=>'csv']))?>">Xuất danh sách ra CSV Excel</a></section>
<section class="md-kpis" aria-label="Chỉ số nâng hạng">
<?php foreach([['Doanh thu xác nhận trong kỳ',md_money($stats['revenue']),'Chỉ tính giao dịch PAID','#168653'],['Gói đã kích hoạt trong kỳ',number_format((int)$stats['packages']),'Theo ngày xác nhận thanh toán','#5d8be2'],['Khách đã nâng hạng',number_format((int)$stats['customers']),'Số khách khác nhau trong kỳ','#dda638'],['Giao dịch tạo trong kỳ',number_format($created),'Tất cả trạng thái, theo ngày tạo','#8966d5']] as $k):?><article class="box md-kpi" style="--accent:<?=$k[3]?>"><span><?=md_e($k[0])?></span><strong><?=md_e($k[1])?></strong><small><?=md_e($k[2])?></small></article><?php endforeach;?></section>
<section class="md-charts"><article class="box md-chart"><h2>Doanh thu theo thời gian</h2><p class="md-note">Doanh thu nâng hạng theo ngày xác nhận thanh toán.</p>
<svg viewBox="0 0 720 245" role="img" aria-label="Biểu đồ doanh thu theo ngày" class="md-line">
<?php for($i=0;$i<=4;$i++):$y=202-$i*165/4;?><line x1="93" y1="<?=$y?>" x2="677" y2="<?=$y?>" stroke="#e5ebe7"/><text x="85" y="<?=$y+4?>" text-anchor="end" font-size="10" fill="#728078"><?=md_money(round($max*$i/4))?></text><?php endfor;?>
<polygon points="<?=md_e(explode(',',$points[0])[0])?>,202 <?=md_e(implode(' ',$points))?> <?=md_e(explode(',',$points[$n-1])[0])?>,202" fill="#e0f2e8"/><polyline points="<?=md_e(implode(' ',$points))?>" fill="none" stroke="#168653" stroke-width="2.5" stroke-linejoin="round"/>
<?php foreach($days as $i=>$d):$xy=explode(',',$points[$i]);if($n<=40||$d['value']>0):?><circle cx="<?=$xy[0]?>" cy="<?=$xy[1]?>" r="<?=$n>40?2:3.5?>" fill="#fff" stroke="#168653" stroke-width="1.4"><title><?=md_e($d['day'].' : '.md_money($d['value']))?></title></circle><?php endif;if($i===0||$i===$n-1||($i%max(1,(int)ceil($n/6))===0&&$i<$n-2)):?><text x="<?=$xy[0]?>" y="222" text-anchor="middle" font-size="10" fill="#728078"><?=date('d/m',strtotime($d['day']))?></text><?php endif;endforeach;?>
</svg><?php if(!$rev):?><p class="md-note">Chưa có doanh thu trong kỳ.</p><?php endif;?></article>
<article class="box md-chart"><h2>Cơ cấu doanh thu theo hạng</h2><p class="md-note">Theo hạng đích của từng giao dịch đã thanh toán.</p><div class="md-donut-layout"><div class="md-donut" role="img" aria-label="<?=md_e('Tổng doanh thu '.md_money($rev))?>" style="background:<?=$rev?'conic-gradient('.implode(',',$segments).')':'#e9eeeb'?>"><div><small>Tổng doanh thu</small><strong><?=md_money($rev)?></strong></div></div><div class="md-legend"><?php foreach($colors as $i=>$color):$r=$breakdown[$i+1]??[];$v=(int)($r['revenue']??0);?><div><span class="md-dot" style="background:<?=$color?>"></span><span><strong><?=$tiers[$i+1]?></strong><small><?=number_format((int)($r['packages']??0))?> gói · <?=md_money($v)?></small></span><b><?=$rev?number_format($v/$rev*100,1,',','.'):0?>%</b></div><?php endforeach;?></div></div></article></section>
<section class="box md-panel"><div class="md-table-title"><div><h2>Danh sách giao dịch nâng hạng</h2><p class="md-note"><?=number_format($total)?> giao dịch phù hợp · <?= $status==='PAID'?'Theo ngày xác nhận':($status==='ALL'?'Theo ngày xác nhận nếu PAID, ngày tạo nếu chưa PAID':'Theo ngày tạo') ?>. Một khách có thể mua nhiều gói.</p></div><a class="md-small" href="<?=md_e(md_url(['status'=>'PAID']))?>">Khách đã thanh toán</a></div><div class="table-wrap"><table><thead><tr><th>Khách hàng</th><th>Mã giao dịch</th><th>Nâng hạng</th><th>Giá gói / đã nhận</th><th>Trạng thái</th><th>Ngày tạo</th><th>Ngày xác nhận</th></tr></thead><tbody>
<?php if(!$rows):?><tr><td colspan="7" class="md-empty">Không có giao dịch phù hợp bộ lọc.</td></tr><?php endif;foreach($rows as $r):?><tr><td><strong><?=md_e(trim($r['customer'])?:'Khách #'.$r['user_id'])?></strong><small class="md-cell-sub"><?=md_e($r['email'])?></small><small class="md-cell-sub"><?=md_e($r['sdt'])?> · ID <?= (int)$r['user_id'] ?></small></td><td><?=md_e($r['order_code'])?></td><td><?=md_e($tiers[$r['from_rank']]??'?')?> → <strong><?=md_e($tiers[$r['target_rank']]??'?')?></strong></td><td><strong><?=md_money($r['amount'])?></strong><small class="md-cell-sub">Đã nhận: <?=md_money($r['amount_received'])?></small></td><td><span class="md-state md-<?=md_e(strtolower($r['status']))?>"><?=md_e($statuses[$r['status']]??$r['status'])?></span></td><td><?=md_e($r['created_at'])?></td><td><?=md_e($r['paid_at']??'—')?></td></tr><?php endforeach;?></tbody></table></div><div class="md-pagination"><span>Trang <?=$pageNum?> / <?=$pages?></span><?php if($pageNum>1):?><a class="md-small" href="<?=md_e(md_url(['p'=>$pageNum-1]))?>">← Trước</a><?php endif;if($pageNum<$pages):?><a class="md-small" href="<?=md_e(md_url(['p'=>$pageNum+1]))?>">Sau →</a><?php endif;?></div></section>
<?php endif;?></main></div></body></html>
