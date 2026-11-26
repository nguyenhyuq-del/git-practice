<?php
require_once dirname(__DIR__) . '/app/bootstrap.php';
function ac_e($value): string {
    return AdminCustomerController::customers_ac_e($value);
}
function ac_money($value): string {
    return AdminCustomerController::customers_ac_money($value);
}
function ac_get(string $key): string {
    return AdminCustomerController::customers_ac_get($key);
}
function ac_id($value): ?int {
    return AdminCustomerController::customers_ac_id($value);
}
function ac_query(mysqli $conn, string $sql, string $types = '', array $params = []): array {
    return AdminCustomerController::customers_ac_query($conn, $sql, $types, $params);
}
function ac_redirect(bool $ok, string $message): void {
    AdminCustomerController::customers_ac_redirect($ok, $message);
}
function goiYChamSoc(string $hang): string {
    return AdminCustomerController::customers_goiYChamSoc($hang);
}
function ac_percent(int $current, int $target): int {
    return AdminCustomerController::customers_ac_percent($current, $target);
}
function ac_voucher_error(array $voucher, string $tier, int $mine): string {
    return AdminCustomerController::customers_ac_voucher_error($voucher, $tier, $mine);
}
extract((new AdminCustomerController())->customers(), EXTR_OVERWRITE);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý khách hàng</title>

    <style>
        *{box-sizing:border-box}
        body{margin:0;font-family:Arial;background:#f4f6f8}
        .layout{display:grid;grid-template-columns:260px minmax(0,1fr);min-height:100vh}
        .sidebar{background:#15985d;color:white;padding:25px 20px}
        .sidebar h2{font-size:30px;margin-bottom:35px}
        .sidebar a{display:block;color:white;text-decoration:none;padding:14px 12px;border-radius:8px;margin-bottom:8px;font-weight:bold}
        .sidebar a:hover,.sidebar a.active{background:rgba(255,255,255,.18)}
        .main{padding:30px;min-width:0}
        .topbar,.box{background:white;border-radius:14px;padding:22px;box-shadow:0 4px 14px rgba(0,0,0,.08)}
        .topbar{margin-bottom:25px}
        .filter-bar{display:flex;gap:12px;align-items:center;margin-bottom:20px;flex-wrap:wrap}
        .filter-bar select,.filter-bar input{height:42px;border:1px solid #ddd;border-radius:8px;padding:0 12px}
        .filter-bar input{width:380px}
        .filter-bar button,.filter-bar a{height:42px;display:inline-flex;align-items:center;justify-content:center;padding:0 16px;border-radius:8px;border:0;font-weight:bold;text-decoration:none;cursor:pointer}
        .filter-bar button{background:#15985d;color:white}
        .filter-bar a{background:#777;color:white}
        .table-wrap{width:100%;overflow-x:auto}
        table{width:100%;min-width:1950px;border-collapse:collapse}
        th,td{padding:13px;border-bottom:1px solid #eee;text-align:left;vertical-align:middle}
        th{background:#f0f0f0}
        .detail{color:#0b72e7;font-weight:bold;text-decoration:none}
        .delete{color:red;font-weight:bold;text-decoration:none}
        .buy-count{background:#dff7ff;color:#0072a3;padding:6px 12px;border-radius:20px;font-weight:bold}
        .rank{display:inline-block;padding:7px 12px;border-radius:20px;font-weight:bold}
        .Member{background:#e9f7ef;color:#15985d}
        .Silver{background:#eeeeee;color:#666}
        .Gold{background:#fff0c7;color:#b66a00}
        .Platinum{background:#e8f1ff;color:#0b72e7}
        .Diamond{background:#ffe1f1;color:#c2185b}
        .main{overflow-wrap:anywhere}
        .filter-bar input{max-width:100%}
        .notice{padding:12px 15px;border-radius:8px;background:#fee2e2;color:#991b1b;margin-bottom:16px}
        .notice-ok{background:#e9f7ef;color:#137a43}
        .stats{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:20px}
        .stat{border:1px solid #eee;border-radius:10px;padding:12px;min-width:120px;flex:1}
        .stat b{display:block;font-size:20px;margin-top:6px;color:#15985d}
        .muted{font-size:13px;color:#666;line-height:1.5}
        .progress-cell{min-width:240px}
        .progress{height:8px;border-radius:8px;background:#eee;overflow:hidden;margin:5px 0}
        .progress span{display:block;background:#15985d;height:100%}
        .voucher-cell{min-width:260px;max-width:360px}
        .voucher-item{border-bottom:1px solid #eee;padding:7px 0;line-height:1.5}
        .voucher-item:last-child{border-bottom:0}
        .voucher-item b,.saved{color:#15985d}
        details summary{cursor:pointer;color:#0b72e7;font-weight:bold}
        .money,.actions{white-space:nowrap}
        .delete-form{display:inline;margin:0}
        .delete-form button{background:none;border:0;cursor:pointer;font:inherit;padding:0;color:red;font-weight:bold}
        .detail-panel{margin-bottom:24px;border:1px solid #ddd;border-radius:10px;padding:18px}
        .detail-panel table{min-width:1000px}
        .detail-panel h2{margin-top:0}
        .detail-panel p{line-height:1.5}
        @media(max-width:800px){.layout{grid-template-columns:1fr}.main{padding:15px}.box{padding:15px}}
    </style>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="layout">

<?php
$page = "customers";
include __DIR__ . '/sidebar.php';
?>

    <main class="main">
        <div class="topbar">
            <h1>Quản lý khách hàng</h1>
        </div>

        <div class="box">

            <?php if (is_array($flash)): ?>
                <div role="status" class="notice <?= !empty($flash['ok']) ? 'notice-ok' : '' ?>"><?= ac_e($flash['message'] ?? '') ?></div>
            <?php endif; ?>
            <?php foreach ($errors as $error): ?>
                <div class="notice" role="alert"><?= ac_e($error) ?></div>
            <?php endforeach; ?>

            <form method="GET" class="filter-bar">
                <select name="rank" aria-label="Hạng thành viên">
                    <option value="all" <?= $rankFilter === 'all' ? 'selected' : '' ?>>Tất cả hạng</option>
                    <?php foreach ($rules as $tier => $rule): ?>
                        <option value="<?= ac_e($tier) ?>" <?= $rankFilter === $tier ? 'selected' : '' ?>><?= ac_e($tier) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="keyword" placeholder="Tìm theo ID, tên, SĐT hoặc email..." value="<?= ac_e($keyword) ?>">
                <button type="submit">Tìm kiếm</button>
                <a href="customers.php">Làm mới</a>
            </form>

            <?php if ($loaded): ?>
                <div class="stats" aria-label="Thống kê theo bộ lọc">
                    <div class="stat">Khách hàng<b><?= count($customers) ?></b></div>
                    <?php foreach ($rankCounts as $tier => $count): ?>
                        <div class="stat"><?= ac_e($tier) ?><b><?= $count ?></b></div>
                    <?php endforeach; ?>
                    <div class="stat">Tiết kiệm thực tế<b><?= ac_money($totalSaved) ?></b></div>
                </div>
            <?php endif; ?>
            <p class="muted">Chi tiêu và số đơn tích lũy chỉ tính đơn đã giao. Đạt một trong hai điều kiện để nâng hạng. Thống kê trên áp dụng cho bộ lọc hiện tại.</p>

            <?php if ($detailCustomer !== null): ?>
                <section class="detail-panel" id="customer-detail">
                    <h2>Chi tiết khách hàng #<?= (int)$detailCustomer['id'] ?></h2>
                    <p><b>Họ tên:</b> <?= ac_e(trim(($detailCustomer['ho'] ?? '') . ' ' . ($detailCustomer['ten'] ?? ''))) ?><br>
                        <b>Email:</b> <?= ac_e($detailCustomer['email'] ?? '') ?><br>
                        <b>SĐT:</b> <?= ac_e($detailCustomer['sdt'] ?? '') ?><br>
                        <b>Địa chỉ:</b> <?= ac_e($detailCustomer['diachi'] ?? '') ?><br>
                        <b>Ngày đăng ký:</b> <?= ac_e($detailCustomer['created_at'] ?? '') ?><br>
                        <b>Hạng hiện tại:</b> <?= ac_e($detailCustomer['member']['tier']) ?> ·
                        <b>Đã giao:</b> <?= (int)$detailCustomer['member']['orders'] ?> đơn ·
                        <b>Chi tiêu:</b> <?= ac_money($detailCustomer['member']['spend']) ?></p>
                    <h3>Tối đa 20 đơn hàng gần nhất</h3>
                    <div class="table-wrap">
                        <table>
                            <thead><tr><th>Mã đơn</th><th>Ngày đặt</th><th>Hạng khi mua</th><th>Voucher</th><th>Giảm sản phẩm</th><th>Giảm vận chuyển</th><th>Tổng tiền</th><th>Trạng thái</th></tr></thead>
                            <tbody>
                            <?php foreach ($detailOrders as $order): ?>
                                <tr>
                                    <td><a class="detail" href="order_detail.php?id=<?= (int)$order['id'] ?>"><?= ac_e($order['ma_don']) ?></a></td>
                                    <td><?= ac_e($order['created_at']) ?></td>
                                    <td><?= ac_e($order['hang_thanh_vien'] ?: 'Chưa ghi nhận') ?></td>
                                    <td><?= ac_e($order['history_code'] ?: ($order['ma_giam_gia'] ?: '—')) ?></td>
                                    <td><?= $order['history_code'] !== null ? ac_money($order['tien_giam_gia']) : '—' ?></td>
                                    <td><?= $order['history_code'] !== null ? ac_money($order['tien_giam_van_chuyen']) : '—' ?></td>
                                    <td class="money"><?= ac_money($order['tong_cong']) ?></td>
                                    <td><?= ac_e($order['trang_thai']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$detailOrders): ?><tr><td colspan="8">Chưa có đơn hàng.</td></tr><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <p><a class="detail" href="customers.php?<?= ac_e(http_build_query($filterParams)) ?>">Đóng chi tiết</a></p>
                </section>
            <?php endif; ?>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Họ tên</th><th>Email</th><th>SĐT</th>
                            <th>Số đơn đã giao</th><th>Tổng đã mua</th><th>Hạng khách</th>
                            <th>Tiến độ nâng hạng</th><th>Quyền lợi voucher</th>
                            <th>Lượt sử dụng</th><th>Tiền tiết kiệm</th><th>Gợi ý chăm sóc</th><th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $row): ?>
                            <?php
                            $member = $row['member'];
                            $hang = $member['tier'];
                            $next = $member['next_tier'];
                            $id = (int)$row['id'];
                            $benefits = [];
                            $available = 0;
                            foreach ($voucherList as $v) {
                                if (!array_key_exists($v['hang_toi_thieu'], $rules) || !MembershipService::canUse($hang, $v['hang_toi_thieu'])) continue;
                                $mine = $userUsage[$id][(int)$v['id']] ?? 0;
                                $reason = ac_voucher_error($v, $hang, $mine);
                                if ($reason === '') $available++;
                                $benefits[] = ['voucher' => $v, 'reason' => $reason, 'mine' => $mine];
                            }
                            ?>
                            <tr>
                                <td><?= $id ?></td>
                                <td><?= ac_e(trim(($row['ho'] ?? '') . ' ' . ($row['ten'] ?? ''))) ?></td>
                                <td><?= ac_e($row['email'] ?? '') ?></td>
                                <td><?= ac_e($row['sdt'] ?? '') ?></td>
                                <td><span class="buy-count"><?= $member['orders'] ?></span></td>
                                <td class="money"><?= ac_money($member['spend']) ?></td>
                                <td><span class="rank <?= ac_e($hang) ?>"><?= ac_e($hang) ?></span></td>
                                <td class="progress-cell">
                                    <?php if ($next !== null): ?>
                                        <?php
                                        $orderPercent = ac_percent($member['orders'], $rules[$next]['orders']);
                                        $spendPercent = ac_percent($member['spend'], $rules[$next]['spend']);
                                        ?>
                                        <b>Lên <?= ac_e($next) ?></b>
                                        <div class="muted"><?= $member['orders'] ?>/<?= $rules[$next]['orders'] ?> đơn (<?= $orderPercent ?>%)</div>
                                        <div class="progress" role="progressbar" aria-label="Tiến độ số đơn" aria-valuenow="<?= $orderPercent ?>" aria-valuemin="0" aria-valuemax="100"><span style="width:<?= $orderPercent ?>%"></span></div>
                                        <div class="muted"><?= ac_money($member['spend']) ?> / <?= ac_money($rules[$next]['spend']) ?> (<?= $spendPercent ?>%)</div>
                                        <div class="progress" role="progressbar" aria-label="Tiến độ chi tiêu" aria-valuenow="<?= $spendPercent ?>" aria-valuemin="0" aria-valuemax="100"><span style="width:<?= $spendPercent ?>%"></span></div>
                                        <div class="muted">Cần thêm <?= $member['orders_needed'] ?> đơn <b>hoặc</b> <?= ac_money($member['spend_needed']) ?>.</div>
                                    <?php else: ?>
                                        <b class="saved">Đã đạt hạng cao nhất</b>
                                    <?php endif; ?>
                                </td>
                                <td class="voucher-cell">
                                    <?php if ($benefits): ?>
                                        <details>
                                            <summary><?= $available ?>/<?= count($benefits) ?> mã đủ điều kiện sơ bộ</summary>
                                            <?php foreach ($benefits as $benefit): $v = $benefit['voucher']; ?>
                                                <div class="voucher-item">
                                                    <b><?= ac_e($v['ma_code']) ?></b> — Giảm <?= (int)$v['phan_tram_giam'] ?>%<?= (int)$v['mien_phi_van_chuyen'] === 1 ? ' + Freeship' : '' ?><br>
                                                    <span class="muted">Đơn từ <?= ac_money($v['don_toi_thieu']) ?> · Tối đa <?= ac_money($v['giam_toi_da']) ?></span><br>
                                                    <span class="muted">Đã dùng <?= $benefit['mine'] ?><?= $v['gioi_han_moi_user'] !== null ? '/' . (int)$v['gioi_han_moi_user'] : '' ?> lượt</span><br>
                                                    <?php if ($benefit['reason'] !== ''): ?>
                                                        <span class="muted"><?= ac_e($benefit['reason']) ?></span>
                                                    <?php else: ?>
                                                        <span class="saved">Có thể dùng nếu đơn đủ điều kiện</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </details>
                                    <?php else: ?>
                                        <span class="muted">Chưa có mã bật phù hợp hạng.</span>
                                    <?php endif; ?>
                                </td>
                                <td><b><?= (int)$row['used_count'] ?></b> lượt chưa hủy<br><span class="muted"><?= (int)$row['delivered_count'] ?> đơn đã giao</span></td>
                                <td class="money"><b class="saved"><?= ac_money($row['saved']) ?></b><br><span class="muted">Dự kiến: <?= ac_money($row['pending_saved']) ?></span></td>
                                <td><?= ac_e(goiYChamSoc($hang)) ?></td>
                                <td class="actions">
                                    <a class="detail" href="customers.php?<?= ac_e(http_build_query(array_merge($filterParams, ['detail' => $id]))) ?>#customer-detail">Chi tiết</a>
                                    |
                                    <form class="delete-form" method="POST" action="customers.php" onsubmit="return confirm('Xóa vĩnh viễn tài khoản, địa chỉ và hồ sơ phụ? Chỉ có thể xóa khách chưa có giao dịch hoặc cuộc trò chuyện.')">
                                        <input type="hidden" name="action" value="delete_customer">
                                        <input type="hidden" name="id" value="<?= $id ?>">
                                        <input type="hidden" name="csrf_token" value="<?= ac_e($token) ?>">
                                        <button type="submit" class="delete">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (!$customers): ?>
                            <tr><td colspan="13" style="text-align:center;"><?= $loaded ? 'Không tìm thấy khách hàng phù hợp' : 'Không thể hiển thị khách hàng.' ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <p class="muted">Lượt voucher không tính đơn đã hủy. Tiết kiệm thực tế gồm tiền giảm sản phẩm và phí vận chuyển của đơn đã giao; dự kiến tính từ đơn chưa giao, chưa hủy. Mã đủ điều kiện sơ bộ vẫn phải kiểm tra giỏ hàng khi thanh toán.</p>
            <p><a class="detail" href="vouchers.php">Quản lý mã giảm giá</a></p>
        </div>
    </main>
</div>
</body>
</html>
