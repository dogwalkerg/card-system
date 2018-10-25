<?php
namespace App\Http\Controllers; use App\System; use Illuminate\Http\Request; use Illuminate\Support\Facades\Log; use Illuminate\Support\Facades\Mail; class DevController extends Controller { private function check_readable_r($sp48c4a9) { if (is_dir($sp48c4a9)) { if (is_readable($sp48c4a9)) { $sp46572f = scandir($sp48c4a9); foreach ($sp46572f as $spaad3ae) { if ($spaad3ae != '.' && $spaad3ae != '..') { if (!self::check_readable_r($sp48c4a9 . '/' . $spaad3ae)) { return false; } else { continue; } } } echo $sp48c4a9 . '   ...... <span style="color: green">R</span><br>'; return true; } else { echo $sp48c4a9 . '   ...... <span style="color: red">R</span><br>'; return false; } } else { if (file_exists($sp48c4a9)) { return is_readable($sp48c4a9); } } echo $sp48c4a9 . '   ...... 文件不存在<br>'; return false; } private function check_writable_r($sp48c4a9) { if (is_dir($sp48c4a9)) { if (is_writable($sp48c4a9)) { $sp46572f = scandir($sp48c4a9); foreach ($sp46572f as $spaad3ae) { if ($spaad3ae != '.' && $spaad3ae != '..') { if (!self::check_writable_r($sp48c4a9 . '/' . $spaad3ae)) { return false; } else { continue; } } } echo $sp48c4a9 . '   ...... <span style="color: green">W</span><br>'; return true; } else { echo $sp48c4a9 . '   ...... <span style="color: red">W</span><br>'; return false; } } else { if (file_exists($sp48c4a9)) { return is_writable($sp48c4a9); } } echo $sp48c4a9 . '   ...... 文件不存在<br>'; return false; } private function checkPathPermission($spcf3029) { self::check_readable_r($spcf3029); self::check_writable_r($spcf3029); } public function install() { $spe0f738 = array(); @ob_start(); self::checkPathPermission(base_path('storage')); self::checkPathPermission(base_path('bootstrap/cache')); $spe0f738['permission'] = @ob_get_clean(); return view('install', array('var' => $spe0f738)); } public function test(Request $sp0fc69c) { } public function test_email() { if (!config('app.debug')) { die; } try { Mail::to('19060@qq.com')->Queue(new \App\Mail\AuthRegister()); return 'success'; } catch (\Exception $sp2a4a9a) { return 'error-' . $sp2a4a9a; } } public function test_alipay_fund_trans_config_dev() { if (!config('app.debug')) { die; } $sp8abf69 = System::_get('alipay_fund_trans_config_dev'); $sp8abf69 = json_decode($sp8abf69, true); $sp8abf69['gateway'] = 'https://openapi.alipaydev.com/gateway.do'; $sp6878ae = date('YmdHis') . random_int(1000, 9999); var_dump($sp6878ae); $spc8aebe = (new \App\Library\AlipayFundTrans\Api())->trans($sp8abf69, $sp6878ae, 'fygtat8407@sandbox.com', '沙箱环境', '1', '转账备注'); if ($spc8aebe === true) { echo '转账成功'; } else { echo '转账失败:' . $spc8aebe; } echo '<br>
'; $spc8aebe = (new \App\Library\AlipayFundTrans\Api())->verify($sp8abf69, $sp6878ae); if ($spc8aebe === true) { echo '验证成功'; } else { echo '验证失败:' . $spc8aebe; } } }