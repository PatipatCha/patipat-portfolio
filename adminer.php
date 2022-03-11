<?php

/** Adminer - Compact database management
 * @link https://www.adminer.org/
 * @author Jakub Vrana, https://www.vrana.cz/
 * @copyright 2007 Jakub Vrana
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 * @version 4.8.1
 */ function
adminer_errors($cc, $ec)
{
	return !!preg_match('~^(Trying to access array offset on value of type null|Undefined array key)~', $ec);
}
error_reporting(6135);
set_error_handler('adminer_errors', E_WARNING);
$wc = !preg_match('~^(unsafe_raw)?$~', ini_get("filter.default"));
if ($wc || ini_get("filter.default_flags")) {
	foreach (array('_GET', '_POST', '_COOKIE', '_SERVER') as $X) {
		$fh = filter_input_array(constant("INPUT$X"), FILTER_UNSAFE_RAW);
		if ($fh) $$X = $fh;
	}
}
if (function_exists("mb_internal_encoding")) mb_internal_encoding("8bit");
function
connection()
{
	global $g;
	return $g;
}
function
adminer()
{
	global $c;
	return $c;
}
function
version()
{
	global $fa;
	return $fa;
}
function
idf_unescape($v)
{
	if (!preg_match('~^[`\'"]~', $v)) return $v;
	$wd = substr($v, -1);
	return
		str_replace($wd . $wd, $wd, substr($v, 1, -1));
}
function
escape_string($X)
{
	return
		substr(q($X), 1, -1);
}
function
number($X)
{
	return
		preg_replace('~[^0-9]+~', '', $X);
}
function
number_type()
{
	return '((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';
}
function
remove_slashes($kf, $wc = false)
{
	if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) {
		while (list($z, $X) = each($kf)) {
			foreach ($X
				as $od => $W) {
				unset($kf[$z][$od]);
				if (is_array($W)) {
					$kf[$z][stripslashes($od)] = $W;
					$kf[] = &$kf[$z][stripslashes($od)];
				} else $kf[$z][stripslashes($od)] = ($wc ? $W : stripslashes($W));
			}
		}
	}
}
function
bracket_escape($v, $_a = false)
{
	static $Sg = array(':' => ':1', ']' => ':2', '[' => ':3', '"' => ':4');
	return
		strtr($v, ($_a ? array_flip($Sg) : $Sg));
}
function
min_version($uh, $Id = "", $h = null)
{
	global $g;
	if (!$h) $h = $g;
	$Sf = $h->server_info;
	if ($Id && preg_match('~([\d.]+)-MariaDB~', $Sf, $C)) {
		$Sf = $C[1];
		$uh = $Id;
	}
	return (version_compare($Sf, $uh) >= 0);
}
function
charset($g)
{
	return (min_version("5.5.3", 0, $g) ? "utf8mb4" : "utf8");
}
function
script($bg, $Rg = "\n")
{
	return "<script" . nonce() . ">$bg</script>$Rg";
}
function
script_src($kh)
{
	return "<script src='" . h($kh) . "'" . nonce() . "></script>\n";
}
function
nonce()
{
	return ' nonce="' . get_nonce() . '"';
}
function
target_blank()
{
	return ' target="_blank" rel="noreferrer noopener"';
}
function
h($lg)
{
	return
		str_replace("\0", "&#0;", htmlspecialchars($lg, ENT_QUOTES, 'utf-8'));
}
function
nl_br($lg)
{
	return
		str_replace("\n", "<br>", $lg);
}
function
checkbox($E, $Y, $Na, $sd = "", $te = "", $Ra = "", $td = "")
{
	$K = "<input type='checkbox' name='$E' value='" . h($Y) . "'" . ($Na ? " checked" : "") . ($td ? " aria-labelledby='$td'" : "") . ">" . ($te ? script("qsl('input').onclick = function () { $te };", "") : "");
	return ($sd != "" || $Ra ? "<label" . ($Ra ? " class='$Ra'" : "") . ">$K" . h($sd) . "</label>" : $K);
}
function
optionlist($xe, $Nf = null, $oh = false)
{
	$K = "";
	foreach ($xe
		as $od => $W) {
		$ye = array($od => $W);
		if (is_array($W)) {
			$K .= '<optgroup label="' . h($od) . '">';
			$ye = $W;
		}
		foreach ($ye
			as $z => $X) $K .= '<option' . ($oh || is_string($z) ? ' value="' . h($z) . '"' : '') . (($oh || is_string($z) ? (string)$z : $X) === $Nf ? ' selected' : '') . '>' . h($X);
		if (is_array($W)) $K .= '</optgroup>';
	}
	return $K;
}
function
html_select($E, $xe, $Y = "", $se = true, $td = "")
{
	if ($se) return "<select name='" . h($E) . "'" . ($td ? " aria-labelledby='$td'" : "") . ">" . optionlist($xe, $Y) . "</select>" . (is_string($se) ? script("qsl('select').onchange = function () { $se };", "") : "");
	$K = "";
	foreach ($xe
		as $z => $X) $K .= "<label><input type='radio' name='" . h($E) . "' value='" . h($z) . "'" . ($z == $Y ? " checked" : "") . ">" . h($X) . "</label>";
	return $K;
}
function
select_input($wa, $xe, $Y = "", $se = "", $Xe = "")
{
	$_g = ($xe ? "select" : "input");
	return "<$_g$wa" . ($xe ? "><option value=''>$Xe" . optionlist($xe, $Y, true) . "</select>" : " size='10' value='" . h($Y) . "' placeholder='$Xe'>") . ($se ? script("qsl('$_g').onchange = $se;", "") : "");
}
function
confirm($D = "", $Of = "qsl('input')")
{
	return
		script("$Of.onclick = function () { return confirm('" . ($D ? js_escape($D) : lang(0)) . "'); };", "");
}
function
print_fieldset($u, $Ad, $xh = false)
{
	echo "<fieldset><legend>", "<a href='#fieldset-$u'>$Ad</a>", script("qsl('a').onclick = partial(toggle, 'fieldset-$u');", ""), "</legend>", "<div id='fieldset-$u'" . ($xh ? "" : " class='hidden'") . ">\n";
}
function
bold($Ga, $Ra = "")
{
	return ($Ga ? " class='active $Ra'" : ($Ra ? " class='$Ra'" : ""));
}
function
odd($K = ' class="odd"')
{
	static $t = 0;
	if (!$K) $t = -1;
	return ($t++ % 2 ? $K : '');
}
function
js_escape($lg)
{
	return
		addcslashes($lg, "\r\n'\\/");
}
function
json_row($z, $X = null)
{
	static $xc = true;
	if ($xc) echo "{";
	if ($z != "") {
		echo ($xc ? "" : ",") . "\n\t\"" . addcslashes($z, "\r\n\t\"\\/") . '": ' . ($X !== null ? '"' . addcslashes($X, "\r\n\"\\/") . '"' : 'null');
		$xc = false;
	} else {
		echo "\n}\n";
		$xc = true;
	}
}
function
ini_bool($cd)
{
	$X = ini_get($cd);
	return (preg_match('~^(on|true|yes)$~i', $X) || (int)$X);
}
function
sid()
{
	static $K;
	if ($K === null) $K = (SID && !($_COOKIE && ini_bool("session.use_cookies")));
	return $K;
}
function
set_password($th, $O, $V, $G)
{
	$_SESSION["pwds"][$th][$O][$V] = ($_COOKIE["adminer_key"] && is_string($G) ? array(encrypt_string($G, $_COOKIE["adminer_key"])) : $G);
}
function
get_password()
{
	$K = get_session("pwds");
	if (is_array($K)) $K = ($_COOKIE["adminer_key"] ? decrypt_string($K[0], $_COOKIE["adminer_key"]) : false);
	return $K;
}
function
q($lg)
{
	global $g;
	return $g->quote($lg);
}
function
get_vals($I, $d = 0)
{
	global $g;
	$K = array();
	$J = $g->query($I);
	if (is_object($J)) {
		while ($L = $J->fetch_row()) $K[] = $L[$d];
	}
	return $K;
}
function
get_key_vals($I, $h = null, $Vf = true)
{
	global $g;
	if (!is_object($h)) $h = $g;
	$K = array();
	$J = $h->query($I);
	if (is_object($J)) {
		while ($L = $J->fetch_row()) {
			if ($Vf) $K[$L[0]] = $L[1];
			else $K[] = $L[0];
		}
	}
	return $K;
}
function
get_rows($I, $h = null, $m = "<p class='error'>")
{
	global $g;
	$fb = (is_object($h) ? $h : $g);
	$K = array();
	$J = $fb->query($I);
	if (is_object($J)) {
		while ($L = $J->fetch_assoc()) $K[] = $L;
	} elseif (!$J && !is_object($h) && $m && defined("PAGE_HEADER")) echo $m . error() . "\n";
	return $K;
}
function
unique_array($L, $x)
{
	foreach ($x
		as $w) {
		if (preg_match("~PRIMARY|UNIQUE~", $w["type"])) {
			$K = array();
			foreach ($w["columns"] as $z) {
				if (!isset($L[$z])) continue
					2;
				$K[$z] = $L[$z];
			}
			return $K;
		}
	}
}
function
escape_key($z)
{
	if (preg_match('(^([\w(]+)(' . str_replace("_", ".*", preg_quote(idf_escape("_"))) . ')([ \w)]+)$)', $z, $C)) return $C[1] . idf_escape(idf_unescape($C[2])) . $C[3];
	return
		idf_escape($z);
}
function
where($Z, $o = array())
{
	global $g, $y;
	$K = array();
	foreach ((array)$Z["where"] as $z => $X) {
		$z = bracket_escape($z, 1);
		$d = escape_key($z);
		$K[] = $d . ($y == "sql" && is_numeric($X) && preg_match('~\.~', $X) ? " LIKE " . q($X) : ($y == "mssql" ? " LIKE " . q(preg_replace('~[_%[]~', '[\0]', $X)) : " = " . unconvert_field($o[$z], q($X))));
		if ($y == "sql" && preg_match('~char|text~', $o[$z]["type"]) && preg_match("~[^ -@]~", $X)) $K[] = "$d = " . q($X) . " COLLATE " . charset($g) . "_bin";
	}
	foreach ((array)$Z["null"] as $z) $K[] = escape_key($z) . " IS NULL";
	return
		implode(" AND ", $K);
}
function
where_check($X, $o = array())
{
	parse_str($X, $Ma);
	remove_slashes(array(&$Ma));
	return
		where($Ma, $o);
}
function
where_link($t, $d, $Y, $ue = "=")
{
	return "&where%5B$t%5D%5Bcol%5D=" . urlencode($d) . "&where%5B$t%5D%5Bop%5D=" . urlencode(($Y !== null ? $ue : "IS NULL")) . "&where%5B$t%5D%5Bval%5D=" . urlencode($Y);
}
function
convert_fields($e, $o, $N = array())
{
	$K = "";
	foreach ($e
		as $z => $X) {
		if ($N && !in_array(idf_escape($z), $N)) continue;
		$ua = convert_field($o[$z]);
		if ($ua) $K .= ", $ua AS " . idf_escape($z);
	}
	return $K;
}
function
cookie($E, $Y, $Dd = 2592000)
{
	global $ba;
	return
		header("Set-Cookie: $E=" . urlencode($Y) . ($Dd ? "; expires=" . gmdate("D, d M Y H:i:s", time() + $Dd) . " GMT" : "") . "; path=" . preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]) . ($ba ? "; secure" : "") . "; HttpOnly; SameSite=lax", false);
}
function
restart_session()
{
	if (!ini_bool("session.use_cookies")) session_start();
}
function
stop_session($zc = false)
{
	$nh = ini_bool("session.use_cookies");
	if (!$nh || $zc) {
		session_write_close();
		if ($nh && @ini_set("session.use_cookies", false) === false) session_start();
	}
}
function &get_session($z)
{
	return $_SESSION[$z][DRIVER][SERVER][$_GET["username"]];
}
function
set_session($z, $X)
{
	$_SESSION[$z][DRIVER][SERVER][$_GET["username"]] = $X;
}
function
auth_url($th, $O, $V, $k = null)
{
	global $Kb;
	preg_match('~([^?]*)\??(.*)~', remove_from_uri(implode("|", array_keys($Kb)) . "|username|" . ($k !== null ? "db|" : "") . session_name()), $C);
	return "$C[1]?" . (sid() ? SID . "&" : "") . ($th != "server" || $O != "" ? urlencode($th) . "=" . urlencode($O) . "&" : "") . "username=" . urlencode($V) . ($k != "" ? "&db=" . urlencode($k) : "") . ($C[2] ? "&$C[2]" : "");
}
function
is_ajax()
{
	return ($_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest");
}
function
redirect($B, $D = null)
{
	if ($D !== null) {
		restart_session();
		$_SESSION["messages"][preg_replace('~^[^?]*~', '', ($B !== null ? $B : $_SERVER["REQUEST_URI"]))][] = $D;
	}
	if ($B !== null) {
		if ($B == "") $B = ".";
		header("Location: $B");
		exit;
	}
}
function
query_redirect($I, $B, $D, $sf = true, $jc = true, $qc = false, $Gg = "")
{
	global $g, $m, $c;
	if ($jc) {
		$hg = microtime(true);
		$qc = !$g->query($I);
		$Gg = format_time($hg);
	}
	$dg = "";
	if ($I) $dg = $c->messageQuery($I, $Gg, $qc);
	if ($qc) {
		$m = error() . $dg . script("messagesPrint();");
		return
			false;
	}
	if ($sf) redirect($B, $D . $dg);
	return
		true;
}
function
queries($I)
{
	global $g;
	static $nf = array();
	static $hg;
	if (!$hg) $hg = microtime(true);
	if ($I === null) return
		array(implode("\n", $nf), format_time($hg));
	$nf[] = (preg_match('~;$~', $I) ? "DELIMITER ;;\n$I;\nDELIMITER " : $I) . ";";
	return $g->query($I);
}
function
apply_queries($I, $S, $fc = 'table')
{
	foreach ($S
		as $Q) {
		if (!queries("$I " . $fc($Q))) return
			false;
	}
	return
		true;
}
function
queries_redirect($B, $D, $sf)
{
	list($nf, $Gg) = queries(null);
	return
		query_redirect($nf, $B, $D, $sf, false, !$sf, $Gg);
}
function
format_time($hg)
{
	return
		lang(1, max(0, microtime(true) - $hg));
}
function
relative_uri()
{
	return
		str_replace(":", "%3a", preg_replace('~^[^?]*/([^?]*)~', '\1', $_SERVER["REQUEST_URI"]));
}
function
remove_from_uri($Le = "")
{
	return
		substr(preg_replace("~(?<=[?&])($Le" . (SID ? "" : "|" . session_name()) . ")=[^&]*&~", '', relative_uri() . "&"), 0, -1);
}
function
pagination($F, $qb)
{
	return " " . ($F == $qb ? $F + 1 : '<a href="' . h(remove_from_uri("page") . ($F ? "&page=$F" . ($_GET["next"] ? "&next=" . urlencode($_GET["next"]) : "") : "")) . '">' . ($F + 1) . "</a>");
}
function
get_file($z, $yb = false)
{
	$uc = $_FILES[$z];
	if (!$uc) return
		null;
	foreach ($uc
		as $z => $X) $uc[$z] = (array)$X;
	$K = '';
	foreach ($uc["error"] as $z => $m) {
		if ($m) return $m;
		$E = $uc["name"][$z];
		$Og = $uc["tmp_name"][$z];
		$gb = file_get_contents($yb && preg_match('~\.gz$~', $E) ? "compress.zlib://$Og" : $Og);
		if ($yb) {
			$hg = substr($gb, 0, 3);
			if (function_exists("iconv") && preg_match("~^\xFE\xFF|^\xFF\xFE~", $hg, $yf)) $gb = iconv("utf-16", "utf-8", $gb);
			elseif ($hg == "\xEF\xBB\xBF") $gb = substr($gb, 3);
			$K .= $gb . "\n\n";
		} else $K .= $gb;
	}
	return $K;
}
function
upload_error($m)
{
	$Od = ($m == UPLOAD_ERR_INI_SIZE ? ini_get("upload_max_filesize") : 0);
	return ($m ? lang(2) . ($Od ? " " . lang(3, $Od) : "") : lang(4));
}
function
repeat_pattern($Ue, $Bd)
{
	return
		str_repeat("$Ue{0,65535}", $Bd / 65535) . "$Ue{0," . ($Bd % 65535) . "}";
}
function
is_utf8($X)
{
	return (preg_match('~~u', $X) && !preg_match('~[\0-\x8\xB\xC\xE-\x1F]~', $X));
}
function
shorten_utf8($lg, $Bd = 80, $pg = "")
{
	if (!preg_match("(^(" . repeat_pattern("[\t\r\n -\x{10FFFF}]", $Bd) . ")($)?)u", $lg, $C)) preg_match("(^(" . repeat_pattern("[\t\r\n -~]", $Bd) . ")($)?)", $lg, $C);
	return
		h($C[1]) . $pg . (isset($C[2]) ? "" : "<i>…</i>");
}
function
format_number($X)
{
	return
		strtr(number_format($X, 0, ".", lang(5)), preg_split('~~u', lang(6), -1, PREG_SPLIT_NO_EMPTY));
}
function
friendly_url($X)
{
	return
		preg_replace('~[^a-z0-9_]~i', '-', $X);
}
function
hidden_fields($kf, $Yc = array(), $df = '')
{
	$K = false;
	foreach ($kf
		as $z => $X) {
		if (!in_array($z, $Yc)) {
			if (is_array($X)) hidden_fields($X, array(), $z);
			else {
				$K = true;
				echo '<input type="hidden" name="' . h($df ? $df . "[$z]" : $z) . '" value="' . h($X) . '">';
			}
		}
	}
	return $K;
}
function
hidden_fields_get()
{
	echo (sid() ? '<input type="hidden" name="' . session_name() . '" value="' . h(session_id()) . '">' : ''), (SERVER !== null ? '<input type="hidden" name="' . DRIVER . '" value="' . h(SERVER) . '">' : ""), '<input type="hidden" name="username" value="' . h($_GET["username"]) . '">';
}
function
table_status1($Q, $rc = false)
{
	$K = table_status($Q, $rc);
	return ($K ? $K : array("Name" => $Q));
}
function
column_foreign_keys($Q)
{
	global $c;
	$K = array();
	foreach ($c->foreignKeys($Q) as $p) {
		foreach ($p["source"] as $X) $K[$X][] = $p;
	}
	return $K;
}
function
enum_input($U, $wa, $n, $Y, $Yb = null)
{
	global $c;
	preg_match_all("~'((?:[^']|'')*)'~", $n["length"], $Jd);
	$K = ($Yb !== null ? "<label><input type='$U'$wa value='$Yb'" . ((is_array($Y) ? in_array($Yb, $Y) : $Y === 0) ? " checked" : "") . "><i>" . lang(7) . "</i></label>" : "");
	foreach ($Jd[1] as $t => $X) {
		$X = stripcslashes(str_replace("''", "'", $X));
		$Na = (is_int($Y) ? $Y == $t + 1 : (is_array($Y) ? in_array($t + 1, $Y) : $Y === $X));
		$K .= " <label><input type='$U'$wa value='" . ($t + 1) . "'" . ($Na ? ' checked' : '') . '>' . h($c->editVal($X, $n)) . '</label>';
	}
	return $K;
}
function
input($n, $Y, $r)
{
	global $ah, $c, $y;
	$E = h(bracket_escape($n["field"]));
	echo "<td class='function'>";
	if (is_array($Y) && !$r) {
		$ta = array($Y);
		if (version_compare(PHP_VERSION, 5.4) >= 0) $ta[] = JSON_PRETTY_PRINT;
		$Y = call_user_func_array('json_encode', $ta);
		$r = "json";
	}
	$_f = ($y == "mssql" && $n["auto_increment"]);
	if ($_f && !$_POST["save"]) $r = null;
	$Gc = (isset($_GET["select"]) || $_f ? array("orig" => lang(8)) : array()) + $c->editFunctions($n);
	$wa = " name='fields[$E]'";
	if ($n["type"] == "enum") echo
	h($Gc[""]) . "<td>" . $c->editInput($_GET["edit"], $n, $wa, $Y);
	else {
		$Pc = (in_array($r, $Gc) || isset($Gc[$r]));
		echo (count($Gc) > 1 ? "<select name='function[$E]'>" . optionlist($Gc, $r === null || $Pc ? $r : "") . "</select>" . on_help("getTarget(event).value.replace(/^SQL\$/, '')", 1) . script("qsl('select').onchange = functionChange;", "") : h(reset($Gc))) . '<td>';
		$ed = $c->editInput($_GET["edit"], $n, $wa, $Y);
		if ($ed != "") echo $ed;
		elseif (preg_match('~bool~', $n["type"])) echo "<input type='hidden'$wa value='0'>" . "<input type='checkbox'" . (preg_match('~^(1|t|true|y|yes|on)$~i', $Y) ? " checked='checked'" : "") . "$wa value='1'>";
		elseif ($n["type"] == "set") {
			preg_match_all("~'((?:[^']|'')*)'~", $n["length"], $Jd);
			foreach ($Jd[1] as $t => $X) {
				$X = stripcslashes(str_replace("''", "'", $X));
				$Na = (is_int($Y) ? ($Y >> $t) & 1 : in_array($X, explode(",", $Y), true));
				echo " <label><input type='checkbox' name='fields[$E][$t]' value='" . (1 << $t) . "'" . ($Na ? ' checked' : '') . ">" . h($c->editVal($X, $n)) . '</label>';
			}
		} elseif (preg_match('~blob|bytea|raw|file~', $n["type"]) && ini_bool("file_uploads")) echo "<input type='file' name='fields-$E'>";
		elseif (($Eg = preg_match('~text|lob|memo~i', $n["type"])) || preg_match("~\n~", $Y)) {
			if ($Eg && $y != "sqlite") $wa .= " cols='50' rows='12'";
			else {
				$M = min(12, substr_count($Y, "\n") + 1);
				$wa .= " cols='30' rows='$M'" . ($M == 1 ? " style='height: 1.2em;'" : "");
			}
			echo "<textarea$wa>" . h($Y) . '</textarea>';
		} elseif ($r == "json" || preg_match('~^jsonb?$~', $n["type"])) echo "<textarea$wa cols='50' rows='12' class='jush-js'>" . h($Y) . '</textarea>';
		else {
			$Qd = (!preg_match('~int~', $n["type"]) && preg_match('~^(\d+)(,(\d+))?$~', $n["length"], $C) ? ((preg_match("~binary~", $n["type"]) ? 2 : 1) * $C[1] + ($C[3] ? 1 : 0) + ($C[2] && !$n["unsigned"] ? 1 : 0)) : ($ah[$n["type"]] ? $ah[$n["type"]] + ($n["unsigned"] ? 0 : 1) : 0));
			if ($y == 'sql' && min_version(5.6) && preg_match('~time~', $n["type"])) $Qd += 7;
			echo "<input" . ((!$Pc || $r === "") && preg_match('~(?<!o)int(?!er)~', $n["type"]) && !preg_match('~\[\]~', $n["full_type"]) ? " type='number'" : "") . " value='" . h($Y) . "'" . ($Qd ? " data-maxlength='$Qd'" : "") . (preg_match('~char|binary~', $n["type"]) && $Qd > 20 ? " size='40'" : "") . "$wa>";
		}
		echo $c->editHint($_GET["edit"], $n, $Y);
		$xc = 0;
		foreach ($Gc
			as $z => $X) {
			if ($z === "" || !$X) break;
			$xc++;
		}
		if ($xc) echo
		script("mixin(qsl('td'), {onchange: partial(skipOriginal, $xc), oninput: function () { this.onchange(); }});");
	}
}
function
process_input($n)
{
	global $c, $l;
	$v = bracket_escape($n["field"]);
	$r = $_POST["function"][$v];
	$Y = $_POST["fields"][$v];
	if ($n["type"] == "enum") {
		if ($Y == -1) return
			false;
		if ($Y == "") return "NULL";
		return +$Y;
	}
	if ($n["auto_increment"] && $Y == "") return
		null;
	if ($r == "orig") return (preg_match('~^CURRENT_TIMESTAMP~i', $n["on_update"]) ? idf_escape($n["field"]) : false);
	if ($r == "NULL") return "NULL";
	if ($n["type"] == "set") return
		array_sum((array)$Y);
	if ($r == "json") {
		$r = "";
		$Y = json_decode($Y, true);
		if (!is_array($Y)) return
			false;
		return $Y;
	}
	if (preg_match('~blob|bytea|raw|file~', $n["type"]) && ini_bool("file_uploads")) {
		$uc = get_file("fields-$v");
		if (!is_string($uc)) return
			false;
		return $l->quoteBinary($uc);
	}
	return $c->processInput($n, $Y, $r);
}
function
fields_from_edit()
{
	global $l;
	$K = array();
	foreach ((array)$_POST["field_keys"] as $z => $X) {
		if ($X != "") {
			$X = bracket_escape($X);
			$_POST["function"][$X] = $_POST["field_funs"][$z];
			$_POST["fields"][$X] = $_POST["field_vals"][$z];
		}
	}
	foreach ((array)$_POST["fields"] as $z => $X) {
		$E = bracket_escape($z, 1);
		$K[$E] = array("field" => $E, "privileges" => array("insert" => 1, "update" => 1), "null" => 1, "auto_increment" => ($z == $l->primary),);
	}
	return $K;
}
function
search_tables()
{
	global $c, $g;
	$_GET["where"][0]["val"] = $_POST["query"];
	$Qf = "<ul>\n";
	foreach (table_status('', true) as $Q => $R) {
		$E = $c->tableName($R);
		if (isset($R["Engine"]) && $E != "" && (!$_POST["tables"] || in_array($Q, $_POST["tables"]))) {
			$J = $g->query("SELECT" . limit("1 FROM " . table($Q), " WHERE " . implode(" AND ", $c->selectSearchProcess(fields($Q), array())), 1));
			if (!$J || $J->fetch_row()) {
				$gf = "<a href='" . h(ME . "select=" . urlencode($Q) . "&where[0][op]=" . urlencode($_GET["where"][0]["op"]) . "&where[0][val]=" . urlencode($_GET["where"][0]["val"])) . "'>$E</a>";
				echo "$Qf<li>" . ($J ? $gf : "<p class='error'>$gf: " . error()) . "\n";
				$Qf = "";
			}
		}
	}
	echo ($Qf ? "<p class='message'>" . lang(9) : "</ul>") . "\n";
}
function
dump_headers($Xc, $Xd = false)
{
	global $c;
	$K = $c->dumpHeaders($Xc, $Xd);
	$Ie = $_POST["output"];
	if ($Ie != "text") header("Content-Disposition: attachment; filename=" . $c->dumpFilename($Xc) . ".$K" . ($Ie != "file" && preg_match('~^[0-9a-z]+$~', $Ie) ? ".$Ie" : ""));
	session_write_close();
	ob_flush();
	flush();
	return $K;
}
function
dump_csv($L)
{
	foreach ($L
		as $z => $X) {
		if (preg_match('~["\n,;\t]|^0|\.\d*0$~', $X) || $X === "") $L[$z] = '"' . str_replace('"', '""', $X) . '"';
	}
	echo
	implode(($_POST["format"] == "csv" ? "," : ($_POST["format"] == "tsv" ? "\t" : ";")), $L) . "\r\n";
}
function
apply_sql_function($r, $d)
{
	return ($r ? ($r == "unixepoch" ? "DATETIME($d, '$r')" : ($r == "count distinct" ? "COUNT(DISTINCT " : strtoupper("$r(")) . "$d)") : $d);
}
function
get_temp_dir()
{
	$K = ini_get("upload_tmp_dir");
	if (!$K) {
		if (function_exists('sys_get_temp_dir')) $K = sys_get_temp_dir();
		else {
			$vc = @tempnam("", "");
			if (!$vc) return
				false;
			$K = dirname($vc);
			unlink($vc);
		}
	}
	return $K;
}
function
file_open_lock($vc)
{
	$q = @fopen($vc, "r+");
	if (!$q) {
		$q = @fopen($vc, "w");
		if (!$q) return;
		chmod($vc, 0660);
	}
	flock($q, LOCK_EX);
	return $q;
}
function
file_write_unlock($q, $sb)
{
	rewind($q);
	fwrite($q, $sb);
	ftruncate($q, strlen($sb));
	flock($q, LOCK_UN);
	fclose($q);
}
function
password_file($i)
{
	$vc = get_temp_dir() . "/adminer.key";
	$K = @file_get_contents($vc);
	if ($K || !$i) return $K;
	$q = @fopen($vc, "w");
	if ($q) {
		chmod($vc, 0660);
		$K = rand_string();
		fwrite($q, $K);
		fclose($q);
	}
	return $K;
}
function
rand_string()
{
	return
		md5(uniqid(mt_rand(), true));
}
function
select_value($X, $A, $n, $Fg)
{
	global $c;
	if (is_array($X)) {
		$K = "";
		foreach ($X
			as $od => $W) $K .= "<tr>" . ($X != array_values($X) ? "<th>" . h($od) : "") . "<td>" . select_value($W, $A, $n, $Fg);
		return "<table cellspacing='0'>$K</table>";
	}
	if (!$A) $A = $c->selectLink($X, $n);
	if ($A === null) {
		if (is_mail($X)) $A = "mailto:$X";
		if (is_url($X)) $A = $X;
	}
	$K = $c->editVal($X, $n);
	if ($K !== null) {
		if (!is_utf8($K)) $K = "\0";
		elseif ($Fg != "" && is_shortable($n)) $K = shorten_utf8($K, max(0, +$Fg));
		else $K = h($K);
	}
	return $c->selectVal($K, $A, $n, $X);
}
function
is_mail($Vb)
{
	$va = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]';
	$Jb = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
	$Ue = "$va+(\\.$va+)*@($Jb?\\.)+$Jb";
	return
		is_string($Vb) && preg_match("(^$Ue(,\\s*$Ue)*\$)i", $Vb);
}
function
is_url($lg)
{
	$Jb = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
	return
		preg_match("~^(https?)://($Jb?\\.)+$Jb(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i", $lg);
}
function
is_shortable($n)
{
	return
		preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea~', $n["type"]);
}
function
count_rows($Q, $Z, $kd, $s)
{
	global $y;
	$I = " FROM " . table($Q) . ($Z ? " WHERE " . implode(" AND ", $Z) : "");
	return ($kd && ($y == "sql" || count($s) == 1) ? "SELECT COUNT(DISTINCT " . implode(", ", $s) . ")$I" : "SELECT COUNT(*)" . ($kd ? " FROM (SELECT 1$I GROUP BY " . implode(", ", $s) . ") x" : $I));
}
function
slow_query($I)
{
	global $c, $T, $l;
	$k = $c->database();
	$Hg = $c->queryTimeout();
	$Zf = $l->slowQuery($I, $Hg);
	if (!$Zf && support("kill") && is_object($h = connect()) && ($k == "" || $h->select_db($k))) {
		$qd = $h->result(connection_id());
		echo '<script', nonce(), '>
var timeout = setTimeout(function () {
	ajax(\'', js_escape(ME), 'script=kill\', function () {
	}, \'kill=', $qd, '&token=', $T, '\');
}, ', 1000 * $Hg, ');
</script>
';
	} else $h = null;
	ob_flush();
	flush();
	$K = @get_key_vals(($Zf ? $Zf : $I), $h, false);
	if ($h) {
		echo
		script("clearTimeout(timeout);");
		ob_flush();
		flush();
	}
	return $K;
}
function
get_token()
{
	$qf = rand(1, 1e6);
	return ($qf ^ $_SESSION["token"]) . ":$qf";
}
function
verify_token()
{
	list($T, $qf) = explode(":", $_POST["token"]);
	return ($qf ^ $_SESSION["token"]) == $T;
}
function
lzw_decompress($Da)
{
	$Gb = 256;
	$Ea = 8;
	$Ta = array();
	$Af = 0;
	$Bf = 0;
	for ($t = 0; $t < strlen($Da); $t++) {
		$Af = ($Af << 8) + ord($Da[$t]);
		$Bf += 8;
		if ($Bf >= $Ea) {
			$Bf -= $Ea;
			$Ta[] = $Af >> $Bf;
			$Af &= (1 << $Bf) - 1;
			$Gb++;
			if ($Gb >> $Ea) $Ea++;
		}
	}
	$Fb = range("\0", "\xFF");
	$K = "";
	foreach ($Ta
		as $t => $Sa) {
		$Ub = $Fb[$Sa];
		if (!isset($Ub)) $Ub = $Ch . $Ch[0];
		$K .= $Ub;
		if ($t) $Fb[] = $Ch . $Ub[0];
		$Ch = $Ub;
	}
	return $K;
}
function
on_help($Za, $Xf = 0)
{
	return
		script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $Za, $Xf) }, onmouseout: helpMouseout});", "");
}
function
edit_form($Q, $o, $L, $ih)
{
	global $c, $y, $T, $m;
	$ug = $c->tableName(table_status1($Q, true));
	page_header(($ih ? lang(10) : lang(11)), $m, array("select" => array($Q, $ug)), $ug);
	$c->editRowPrint($Q, $o, $L, $ih);
	if ($L === false) echo "<p class='error'>" . lang(12) . "\n";
	echo '<form action="" method="post" enctype="multipart/form-data" id="form">
';
	if (!$o) echo "<p class='error'>" . lang(13) . "\n";
	else {
		echo "<table cellspacing='0' class='layout'>" . script("qsl('table').onkeydown = editingKeydown;");
		foreach ($o
			as $E => $n) {
			echo "<tr><th>" . $c->fieldName($n);
			$zb = $_GET["set"][bracket_escape($E)];
			if ($zb === null) {
				$zb = $n["default"];
				if ($n["type"] == "bit" && preg_match("~^b'([01]*)'\$~", $zb, $yf)) $zb = $yf[1];
			}
			$Y = ($L !== null ? ($L[$E] != "" && $y == "sql" && preg_match("~enum|set~", $n["type"]) ? (is_array($L[$E]) ? array_sum($L[$E]) : +$L[$E]) : (is_bool($L[$E]) ? +$L[$E] : $L[$E])) : (!$ih && $n["auto_increment"] ? "" : (isset($_GET["select"]) ? false : $zb)));
			if (!$_POST["save"] && is_string($Y)) $Y = $c->editVal($Y, $n);
			$r = ($_POST["save"] ? (string)$_POST["function"][$E] : ($ih && preg_match('~^CURRENT_TIMESTAMP~i', $n["on_update"]) ? "now" : ($Y === false ? null : ($Y !== null ? '' : 'NULL'))));
			if (!$_POST && !$ih && $Y == $n["default"] && preg_match('~^[\w.]+\(~', $Y)) $r = "SQL";
			if (preg_match("~time~", $n["type"]) && preg_match('~^CURRENT_TIMESTAMP~i', $Y)) {
				$Y = "";
				$r = "now";
			}
			input($n, $Y, $r);
			echo "\n";
		}
		if (!support("table")) echo "<tr>" . "<th><input name='field_keys[]'>" . script("qsl('input').oninput = fieldChange;") . "<td class='function'>" . html_select("field_funs[]", $c->editFunctions(array("null" => isset($_GET["select"])))) . "<td><input name='field_vals[]'>" . "\n";
		echo "</table>\n";
	}
	echo "<p>\n";
	if ($o) {
		echo "<input type='submit' value='" . lang(14) . "'>\n";
		if (!isset($_GET["select"])) {
			echo "<input type='submit' name='insert' value='" . ($ih ? lang(15) : lang(16)) . "' title='Ctrl+Shift+Enter'>\n", ($ih ? script("qsl('input').onclick = function () { return !ajaxForm(this.form, '" . lang(17) . "…', this); };") : "");
		}
	}
	echo ($ih ? "<input type='submit' name='delete' value='" . lang(18) . "'>" . confirm() . "\n" : ($_POST || !$o ? "" : script("focus(qsa('td', qs('#form'))[1].firstChild);")));
	if (isset($_GET["select"])) hidden_fields(array("check" => (array)$_POST["check"], "clone" => $_POST["clone"], "all" => $_POST["all"]));
	echo '<input type="hidden" name="referer" value="', h(isset($_POST["referer"]) ? $_POST["referer"] : $_SERVER["HTTP_REFERER"]), '">
<input type="hidden" name="save" value="1">
<input type="hidden" name="token" value="', $T, '">
</form>
';
}
if (isset($_GET["file"])) {
	if ($_SERVER["HTTP_IF_MODIFIED_SINCE"]) {
		header("HTTP/1.1 304 Not Modified");
		exit;
	}
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + 365 * 24 * 60 * 60) . " GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: immutable");
	if ($_GET["file"] == "favicon.ico") {
		header("Content-Type: image/x-icon");
		echo
		lzw_decompress("\0\0\0` \0�\0\n @\0�C��\"\0`E�Q����?�tvM'�Jd�d\\�b0\0�\"��fӈ��s5����A�XPaJ�0���8�#R�T��z`�#.��c�X��Ȁ?�-\0�Im?�.�M��\0ȯ(̉��/(%�\0");
	} elseif ($_GET["file"] == "default.css") {
		header("Content-Type: text/css; charset=utf-8");
		echo
		lzw_decompress("\n1̇�ٌ�l7��B1�4vb0��fs���n2B�ѱ٘�n:�#(�b.\rDc)��a7E����l�ñ��i1̎s���-4��f�	��i7�����t4���y�Zf4��i�AT�VV��f:Ϧ,:1�Qݼ�b2`�#�>:7G�1���s��L�XD*bv<܌#�e@�:4�!fo���t:<��咾�o��\ni���',�a_�:�i�Bv�|N�4.5Nf�i�vp�h��l��֚�O����= �OFQ��k\$��i����d2T�p��6�����-�Z�����6����h:�a�,����2�#8А�#��6n����J��h�t�����4O42��ok��*r���@p@�!������?�6��r[��L���:2B�j�!Hb��P�=!1V�\"��0��\nS���D7��Dڛ�C!�!��Gʌ� �+�=tC�.C��:+��=�������%�c�1MR/�EȒ4���2�䱠�`�8(�ӹ[W��=�yS�b�=�-ܹBS+ɯ�����@pL4Yd��q�����6�3Ĭ��Ac܌�Ψ�k�[&>���Z�pkm]�u-c:���Nt�δpҝ��8�=�#��[.��ޯ�~���m�y�PP�|I֛���Q�9v[�Q��\n��r�'g�+��T�2��V��z�4��8��(	�Ey*#j�2]��R����)��[N�R\$�<>:�>\$;�>��\r���H��T�\nw�N �wأ��<��Gw����\\Y�_�Rt^�>�\r}��S\rz�4=�\nL�%J��\",Z�8����i�0u�?�����s3#�ى�:���㽖��E]x���s^8��K^��*0��w����~���:��i���v2w����^7���7�c��u+U%�{P�*4̼�LX./!��1C��qx!H��Fd��L���Ġ�`6��5��f��Ć�=H�l �V1��\0a2�;��6����_ه�\0&�Z�S�d)KE'��n��[X��\0ZɊ�F[P�ޘ@��!��Y�,`�\"ڷ��0Ee9yF>��9b����F5:���\0}Ĵ��(\$����37H��� M�A��6R��{Mq�7G��C�C�m2�(�Ct>[�-t�/&C�]�etG�̬4@r>���<�Sq�/���Q�hm���������L��#��K�|���6fKP�\r%t��V=\"�SH\$�} ��)w�,W\0F��u@�b�9�\rr�2�#�D��X���yOI�>��n��Ǣ%���'��_��t\rτz�\\1�hl�]Q5Mp6k���qh�\$�H~�|��!*4����`S���S t�PP\\g��7�\n-�:袪p����l�B���7Өc�(wO0\\:��w���p4���{T��jO�6HÊ�r���q\n��%%�y']\$��a�Z�.fc�q*-�FW��k��z���j���lg�:�\$\"�N�\r#�d�Â���sc�̠��\"j�\r�����Ւ�Ph�1/��DA)���[�kn�p76�Y��R{�M�P���@\n-�a�6��[�zJH,�dl�B�h�o�����+�#Dr^�^��e��E��� ĜaP���JG�z��t�2�X�����V�����ȳ��B_%K=E��b弾�§kU(.!ܮ8����I.@�K�xn���:�P�32��m�H		C*�:v�T�\nR�����0u�����ҧ]�����P/�JQd�{L�޳:Y��2b��T ��3�4���c�V=���L4��r�!�B�Y�6��MeL������i�o�9< G��ƕЙMhm^�U�N����Tr5HiM�/�n�흳T��[-<__�3/Xr(<���������uҖGNX20�\r\$^��:'9�O��;�k����f��N'a����b�,�V��1��HI!%6@��\$�EGڜ�1�(mU��rս���`��iN+Ü�)���0l��f0��[U��V��-:I^��\$�s�b\re��ug�h�~9�߈�b�����f�+0�� hXrݬ�!\$�e,�w+����3��_�A�k��\nk�r�ʛcuWdY�\\�={.�č���g��p8�t\rRZ�v�J:�>��Y|+�@����C�t\r��jt��6��%�?��ǎ�>�/�����9F`ו��v~K�����R�W��z��lm�wL�9Y�*q�x�z��Se�ݛ����~�D�����x���ɟi7�2���Oݻ��_{��53��t���_��z�3�d)�C��\$?KӪP�%��T&��&\0P�NA�^�~���p� �Ϝ���\r\$�����b*+D6궦ψ��J\$(�ol��h&��KBS>���;z��x�oz>��o�Z�\nʋ[�v���Ȝ��2�OxِV�0f�����2Bl�bk�6Zk�hXcd�0*�KT�H=��π�p0�lV����\r���n�m��)(�(�:#����E��:C�C���\r�G\ré0��i����:`Z1Q\n:��\r\0���q���:`�-�M#}1;����q�#|�S���hl�D�\0fiDp�L��``����0y��1���\r�=�MQ\\��%oq��\0��1�21�1�� ���ќbi:��\r�/Ѣ� `)��0��@���I1�N�C�����O��Z��1���q1 ����,�\rdI�Ǧv�j�1 t�B���⁒0:�0��1�A2V���0���%�fi3!&Q�Rc%�q&w%��\r��V�#���Qw`�% ���m*r��y&i�+r{*��(rg(�#(2�(��)R@i�-�� ���1\"\0��R���.e.r��,�ry(2�C��b�!Bޏ3%ҵ,R�1��&��t��b�a\rL��-3�����\0��Bp�1�94�O'R�3*��=\$�[�^iI;/3i�5�&�}17�# ѹ8��\"�7��8�9*�23�!�!1\\\0�8��rk9�;S�23��ړ*�:q]5S<��#3�83�#e�=�>~9S螳�r�)��T*a�@і�bes���:-���*;,�ؙ3!i���LҲ�#1 �+n� �*��@�3i7�1���_�F�S;3�F�\rA��3�>�x:� \r�0��@�-�/��w��7��S�J3� �.F�\$O�B���%4�+t�'g�Lq\rJt�J��M2\r��7��T@���)ⓣd��2�P>ΰ��Fi಴�\nr\0��b�k(�D���KQ����1�\"2t����P�\r��,\$KCt�5��#��)��P#Pi.�U2�C�~�\"�");
	} elseif ($_GET["file"] == "functions.js") {
		header("Content-Type: text/javascript; charset=utf-8");
		echo
		lzw_decompress("f:��gCI��\n8��3)��7���81��x:\nOg#)��r7\n\"��`�|2�gSi�H)N�S��\r��\"0��@�)�`(\$s6O!��V/=��' T4�=��iS��6IO�G#�X�VC��s��Z1.�hp8,�[�H�~Cz���2�l�c3���s���I�b�4\n�F8T��I���U*fz��r0�E����y���f�Y.:��I��(�c��΋!�_l��^�^(��N{S��)r�q�Y��l٦3�3�\n�+G���y���i���xV3w�uh�^r����a۔���c��\r���(.��Ch�<\r)�ѣ�`�7���43'm5���\n�P�:2�P����q ���C�}ī�����38�B�0�hR��r(�0��b\\0�Hr44��B�!�p�\$�rZZ�2܉.Ƀ(\\�5�|\nC(�\"��P���.��N�RT�Γ��>�HN��8HP�\\�7Jp~���2%��OC�1�.��C8·H��*�j����S(�/��6KU����<2�pOI���`���ⳈdO�H��5�-��4��pX25-Ң�ۈ�z7��\"(�P�\\32:]U����߅!]�<�A�ۤ���iڰ�l\r�\0v��#J8��wm��ɤ�<�ɠ��%m;p#�`X�D���iZ��N0����9��占��`��wJ�D��2�9t��*��y��NiIh\\9����:����xﭵyl*�Ȉ��Y�����8�W��?���ޛ3���!\"6�n[��\r�*\$�Ƨ�nzx�9\r�|*3ףp�ﻶ�:(p\\;��mz���9����8N���j2����\r�H�H&��(�z��7i�k� ����c��e���t���2:SH�Ƞ�/)�x�@��t�ri9����8����yҷ���V�+^Wڦ��kZ�Y�l�ʣ���4��Ƌ������\\E�{�7\0�p���D��i�-T����0l�%=���˃9(�5�\n\n�n,4�\0�a}܃.��Rs\02B\\�b1�S�\0003,�XPHJsp�d�K� CA!�2*W����2\$�+�f^\n�1����zE� Iv�\\�2��.*A���E(d���b��܄��9����Dh�&��?�H�s�Q�2�x~nÁJ�T2�&��eR���G�Q��Tw�ݑ��P���\\�)6�����sh\\3�\0R	�'\r+*;R�H�.�!�[�'~�%t< �p�K#�!�l���Le����,���&�\$	��`��CX��ӆ0֭����:M�h	�ڜG��!&3�D�<!�23��?h�J�e ��h�\r�m���Ni�������N�Hl7��v��WI�.��-�5֧ey�\rEJ\ni*�\$@�RU0,\$U�E����ªu)@(t�SJk�p!�~���d`�>��\n�;#\rp9�jɹ�]&Nc(r���TQU��S��\08n`��y�b���L�O5��,��>���x���f䴒���+��\"�I�{kM�[\r%�[	�e�a�1! ����Ԯ�F@�b)R��72��0�\nW���L�ܜҮtd�+���0wgl�0n@��ɢ�i�M��\nA�M5n�\$E�ױN��l�����%�1 A������k�r�iFB���ol,muNx-�_�֤C( ��f�l\r1p[9x(i�BҖ��zQl��8C�	��XU Tb��I�`�p+V\0��;�Cb��X�+ϒ�s��]H��[�k�x�G*�]�awn�!�6�����mS���I��K�~/�ӥ7��eeN��S�/;d�A�>}l~��� �%^�f�آpڜDE��a��t\nx=�kЎ�*d���T����j2��j��\n��� ,�e=��M84���a�j@�T�s���nf��\n�6�\rd��0���Y�'%ԓ��~	�Ҩ�<���AH�G��8���΃\$z��{���u2*��a��>�(w�K.bP�{��o��´�z�#�2�8=�8>���A,�e���+�C�x�*���-b=m���,�a��lzk���\$W�,�m�Ji�ʧ���+���0�[��.R�sK���X��ZL��2�`�(�C�vZ������\$�׹,�D?H��NxX��)��M��\$�,��*\nѣ\$<q�şh!��S����xsA!�:�K��}�������R��A2k�X�p\n<�����l���3�����VV�}�g&Yݍ!�+�;<�Y��YE3r�َ��C�o5����ճ�kk�����ۣ��t��U���)�[����}��u��l�:D��+Ϗ _o��h140���0��b�K�㬒�����lG��#��������|Ud�IK���7�^��@��O\0H��Hi�6\r����\\cg\0���2�B�*e��\n��	�zr�!�nWz&� {H��'\$X �w@�8�DGr*���H�'p#�Į���\nd���,���,�;g~�\0�#����E��\r�I`��'��%E�.�]`�Л��%&��m��\r��%4S�v�#\n��fH\$%�-�#���qB�����Q-�c2���&���]�� �qh\r�l]�s���h�7�n#����-�jE�Fr�l&d����z�F6����\"���|���s@����z)0rpڏ\0�X\0���|DL<!��o�*�D�{.B<E���0nB(� �|\r\n�^���� h�!���r\$��(^�~����/p�q��B��O����,\\��#RR��%���d�Hj�`����̭ V� bS�d�i�E���oh�r<i/k\$-�\$o��+�ŋ��l��O�&evƒ�i�jMPA'u'���( M(h/+��WD�So�.n�.�n���(�(\"���h�&p��/�/1D̊�j娸E��&⦀�,'l\$/.,�d���W�bbO3�B�sH�:J`!�.���������,F��7(��Կ��1�l�s �Ҏ���Ţq�X\r����~R鰱`�Ҟ�Y*�:R��rJ��%L�+n�\"��\r��͇H!qb�2�Li�%����Wj#9��ObE.I:�6�7\0�6+�%�.����a7E8VS�?(DG�ӳB�%;���/<�����\r ��>�M��@���H�Ds��Z[tH�Enx(���R�x��@��GkjW�>���#T/8�c8�Q0��_�IIGII�!���YEd�E�^�td�th�`DV!C�8��\r���b�3�!3�@�33N}�ZB�3	�3�30��M(�>��}�\\�t�f�f���I\r���337 X�\"td�,\nbtNO`P�;�ܕҭ���\$\n����Zѭ5U5WU�^ho���t�PM/5K4Ej�KQ&53GX�Xx)�<5D��\r�V�\n�r�5b܀\\J\">��1S\r[-��Du�\r���)00�Y��ˢ�k{\n��#��\r�^��|�uܻU�_n�U4�U�~Yt�\rI��@䏳�R �3:�uePMS�0T�wW�X���D��KOU����;U�\n�OY��Y�Q,M[\0�_�D���W��J*�\rg(]�\r\"ZC��6u�+�Y��Y6ô�0�q�(��8}��3AX3T�h9j�j�f�Mt�PJbqMP5>������Y�k%&\\�1d��E4� �Yn���\$<�U]Ӊ1�mbֶ�^�����\"NV��p��p��eM���W�ܢ�\\�)\n �\nf7\n�2��r8��=Ek7tV����7P��L��a6��v@'�6i��j&>��;��`��a	\0pڨ(�J��)�\\��n��Ĭm\0��2��eqJ��P��t��fj��\"[\0����X,<\\������+md��~�����s%o��mn�),ׄ�ԇ�\r4��8\r����mE�H]�����HW�M0D�߀��~�ˁ�K��E}����|f�^���\r>�-z]2s�xD�d[s�t�S��\0Qf-K`���t���wT�9��Z��	�\nB�9 Nb��<�B�I5o�oJ�p��JNd��\r�hލ��2�\"�x�HC�ݍ�:���9Yn16��zr+z���\\�����m ��T ���@Y2lQ<2O+�%��.Ӄh�0A���Z��2R��1��/�hH\r�X��aNB&� �M@�[x��ʮ���8&L�V͜v�*�j�ۚGH��\\ٮ	���&s�\0Q��\\\"�b��	��\rBs��w��	���BN`�7�Co(���\nè���1�9�*E� �S��U�0U� t�'|�m���?h[�\$.#�5	 �	p��yB�@R�]���@|��{���P\0x�/� w�%�EsBd���CU�~O׷�P�@X�]����Z3��1��{�eLY���ڐ�\\�(*R`�	�\n������QCF�*�����霬�p�X|`N���\$�[���@�U������Z�`Zd\"\\\"����)��I�:�t��oD�\0[�����-���g�����*`hu%�,����I�7ī�H�m�6�}��N�ͳ\$�M�UYf&1����e]pz���I��m�G/� �w �!�\\#5�4I�d�E�hq���Ѭk�x|�k�qD�b�z?���>���:��[�L�ƬZ�X��:�������j�w5	�Y��0 ���\$\0C��dSg����{�@�\n`�	���C ���M�����# t}x�N����{�۰)��C��FKZ�j��\0PFY�B�pFk��0<�>�D<JE��g\r�.�2��8�U@*�5fk��JD���4��TDU76�/��@��K+���J�����@�=��WIOD�85M��N�\$R�\0�5�\r��_���E���I�ϳN�l���y\\����qU��Q���\n@���ۺ�p���P۱�7ԽN\r�R{*�qm�\$\0R��ԓ���q�È+U@�B��Of*�Cˬ�MC��`_ ���˵N��T�5٦C׻� ��\\W�e&_X�_؍h���B�3���%�FW���|�Gޛ'�[�ł����V��#^\r��GR����P��Fg�����Yi ���z\n��+�^/�������\\�6��b�dmh��@q���Ah�),J��W��cm�em]�ӏe�kZb0�����Y�]ym��f�e�B;���O��w�apDW�����{�\0��-2/bN�sֽ޾Ra�Ϯh&qt\n\"�i�Rm�hz�e����FS7��PP�䖤��:B����sm��Y d���7}3?*�t����lT�}�~�����=c������	��3�;T�L�5*	�~#�A����s�x-7��f5`�#\"N�b��G����@�e�[�����s����-��M6��qq� h�e5�\0Ң���*�b�IS���Fή9}�p�-��`{��ɖkP�0T<��Z9�0<՚\r��;!��g�\r\nK�\n��\0��*�\nb7(�_�@,�e2\r�]�K�+\0��p C\\Ѣ,0�^�MЧ����@�;X\r��?\$\r�j�+�/��B��P�����J{\"a�6�䉜�|�\n\0��\\5���	156�� .�[�Uد\0d��8Y�:!���=��X.�uC����!S���o�p�B���7��ů�Rh�\\h�E=�y:< :u��2�80�si��TsB�@\$ ��@�u	�Q���.��T0M\\/�d+ƃ\n��=��d���A���)\r@@�h3���8.eZa|.�7�Yk�c���'D#��Y�@X�q�=M��44�B AM��dU\"�Hw4�(>��8���C�?e_`��X:�A9ø���p�G��Gy6��F�Xr��l�1��ػ�B�Å9Rz��hB�{����\0��^��-�0�%D�5F\"\"�����i�`��nAf� \"tDZ\"_�V\$��!/�D�ᚆ������٦�̀F,25�j�T��y\0�N�x\r�Yl��#��Eq\n��B2�\n��6���4���!/�\n��Q��*�;)bR�Z0\0�CDo�˞�48������e�\n�S%\\�PIk��(0��u/��G������\\�}�4Fp��G�_�G?)g�ot��[v��\0��?b�;��`(�ی�NS)\n�x=��+@��7��j�0��,�1Åz����>0��Gc��L�VX�����%����Q+���o�F���ܶ�>Q-�c���l����w��z5G��@(h�c�H��r?��Nb�@�������lx3�U`�rw���U���t�8�=�l#���l�䨉8�E\"����O6\n��1e�`\\hKf�V/зPaYK�O�� ��x�	�Oj���r7�F;��B����̒��>�Ц�V\rĖ�|�'J�z����#�PB��Y5\0NC�^\n~LrR��[̟Rì�g�eZ\0x�^�i<Q�/)�%@ʐ��fB�Hf�{%P�\"\"���@���)���DE(iM2�S�*�y�S�\"���e̒1��ט\n4`ʩ>��Q*��y�n����T�u�����~%�+W��XK���Q�[ʔ��l�PYy#D٬D<�FL���@�6']Ƌ��\rF�`�!�%\n�0�c���˩%c8WrpG�.T�Do�UL2�*�|\$�:�Xt5�XY�I�p#� �^\n��:�#D�@�1\r*�K7�@D\0��C�C�xBh�EnK�,1\"�*y[�#!�י�ٙ���l_�/��x�\0���5�Z��4\0005J�h\"2���%Y���a�a1S�O�4��%ni��P��ߴq�_ʽ6���~��I\\���d���d������D�����3g^��@^6����_�HD�.ksL��@��Ɉ�n�I���~�\r�b�@�Ӏ�N�t\0s���]:u��X�b@^�1\0���2?�T��6dLNe��+�\0�:�Ё�l��z6q=̺x���N6��O,%@s�0\n�\\)�L<�C�|���P��b����A>I���\"	��^K4��gIX�i@P�jE�&/1@�f�	�N�x0coaߧ����,C'�y#6F@�Р��H0�{z3t�|cXMJ.*B�)ZDQ���\0��T-v�X�a*��,*�<b���#xј�d�P��KG8�� y�K	\\#=�)�gȑh�&�8])�C�\nô��9�z�W\\�g�M 7��!��������,��9���\$T\"�,��%.F!˚ A�-�����-�g��\0002R>KE�'�U�_I���9�˼�j(�Q��@�@�4/�7���'J.�RT�\0]KS�D���Ap5�\r�H0!�´e	d@Rҝ�ิ�9�S�;7�H�B�bx�J��_�vi�U`@���SAM��X��G�Xi��U*��������'��:V�WJv�D���N'\$�zh\$d_y���Z]����Y���8ؔ���]�P�*h���֧e;��pe��\$k�w��*7N�DTx_�ԧ�Gi�&P�Ԇ�t͆�b�\\E�H\$i�E\"cr��0l�?>��C(�W@3���22a���I����{�B`�ڳiŸGo^6E\r��G�M�p1i�I��X�\0003�2�K�����zl&ֆ�'IL�\\�\"�7�>�j(>�j�FG_��& 10I�A31=h q\0�F����ķ��_�J���ԳVΖ��܆q�՚��	��(/�dOC�_sm�<g�x\0��\"��\n@EkH\0�J���8�(���km[����S4�\nY40��+L\n������#Bӫb��%R֖��׭��R:�<\$!ۥr�;���	%|ʨ�(�|�H�\0�������]�cҡ=0��Z�\"\"=�X��)�f�N��6V}F��=[���ৢhu�-��\0t��bW~��Q��iJ���L�5׭q#kb���Wn���Q�T�!���e�nc�S�[+ִE�<-��a]Ń��Yb�\n\nJ~�|JɃ8� �Lp����o� �N�ܨ�J.��ŃS��2c9�j�y�-`a\0��*�ֈ@\0+��mg��6�1��Me\0��Q �_�}!I��GL�f)�X�o,�Shx�\0000\"h�+L�M�� �ј��Z	j�\0���/��\$��>u*�Z9��Z�e��+J����tz������R�Kԯ���Dy���q�0C�-f��m����BI�|��HB��sQl�X��.����|�c���[��ZhZ��l���x�@'��ml�KrQ�26��]�ҷn�d[��񎩇d���\"GJ9u��B�o��Zߖ�a��n@��n�lW|*gX�\nn2�F�|x`Dk��uPP�!Q\rr��`W/���	1�[-o,71bUs����N�7����Gq�.\\Q\"CCT\"�����*?u�ts�����]�٩Pz[�[YFϹ��FD3�\"����]�u۝)wz�:#���Iiw��pɛ��{�o�0n��;��\\�x���\0q��m����&�~��7����9[�H�qdL�O�2�v�|B�t��\\Ƥ�Hd���H�\" ��N\n\0��G�g�F��F�}\"�&QEK��{}\ryǎ��rכt������7�Nuó[A�gh;S�.Ҡ���¥|y��[Ն_b�Ȩ�!+R��ZX�@0N����P���%�jD�¯z	���[�U\"�{e�8��>�EL4Jн�0����7 ��d�� �Q^`0`�����]c�<g@��hy8��p.ef\n��eh��aX����mS��jBژQ\"�\r���K3�=>ǪAX�[,,\"'<���%�a��Ӵ��.\$�\0�%\0�sV���p�M\$�@j���>���}Ve�\$@�̈́#���(3:�`�U�Y��u�������@�V#E�G/��XD\$�h��av��xS\"]k18a�я�9dJROӊs�`EJ����Uo�m{l�B8���(\n}ei�b��, �;�N��͇�Q�\\�ǸI5yR�\$!>\\ʉ�g�uj*?n�M�޲h��\r%���U(d��N�d#}�pA:����-\\�A�*�4�2I���\r�֣�� 0h@\\Ե��8�3�rq]���d8\"�Q����ƙ:c��y�4	�ᑚda�Π6>U�A����:��@�2���\$�eh2���F��əN�+���\r�Ԁ(�Ar��d*�\0[�#cj����>!(�S���L�e�T��M	9\0W:�BD���3J���_@s��rue������ +�'B��}\"B\"�z2��r��l�xF[�L�˲Ea9��cdb��^,�UC=/2�����/\$�C�#��8�}D���6�`^;6B0U7�_=	,�1�j1V[�.	H9(1���ҏLz�C�	�\$.A�fh㖫����DrY	�H�e~o�r19��م\\�߄P�)\"�Q��,�e��L��w0�\0������;w�X�ǝ���qo���~�����>9�>}��dc�\0��g��f��q�&9���-�J#����3^4m/���\0\0006��n8��>䈴.ӗ��cph��������_A@[��7�|9\$pMh�>���5�K���E=h��A�t�^�V�	�\"�	c�B;���i��QҠt����@,\n�)���s�`����;�4����I�������y��-�0yeʨ�U��B�v��3H�P�G�5��s|��\r���\$0����1��l3��(*oF~PK��.�,'�J/�Ӳ�t���d�:��n�\n��j��Y�z�(����w���Z�#Z�	Io�@1�λ\$��=VWz�	n�B�a���A��q�@��I�p	@�5Ӗ�lH{U��oX��f��ӿ\\z��.���,-\\ڗ^y n^���Bq����zX㉡�\$�*J72�D4.����!�M0��D��F����G��L�m�c*m�cI��5Ɍ�^�t���jl�7替S�Q��.i����h��L�ڱB6Ԅh�&�J��l\\��We�c�f%kj�� �p�R=��i�@.��(�2�klHUW\"�o�j���p!S5��pL'`\0�O *�Q3X��lJ\08\n�\r���*�a��떞��r�`<�&�XBh�8!x��&�Bht�\$���]�n߆���cL��[Ƶ�d��<`���\0���ς�aw�O%;���BC��Q�\r̭�����p����PQ�Z���Z�Au=N&�ia\n�mK6I}��n	��t\nd)�����bp��\"��g'�0�7�u�&@�7�8X�N��x������\$B��ZB/�M�gB�i��ѧ�\\�m�mI�Ā��;5=#&4����P�Ս����q�A��\\�,q�cޟ\nc�B�����w\0BgjD�@;�=0m�k��\rĲ�`��'5���k-�{��\0�_�Mu����2��׆����q����>)9�W\n�d+��ԧ�G\r��n4���O�:5���8��1�:Κ?��(yGgWK�\r�7����m5.��e�H�hJ�Ak#��L�..�\\�=��U�Є����:�>7�W+^yD���b��G��OZ�4�r�(|x���Pr��,y���8qaܩO2��k�n��#p2��ǈ�ؔ.��c��U�c����łj�\$��8Ĭ~��7ZR:�׆8�9Ψw(a�L�%�-,��쿌#�f�%8��|�c������%X�W�\n}6��H����˞��#�&J,'z�M�M�����ຑ܆� ���/y6YQ���ںdәd����:����E��p2g�g�/�,����Ո'8�^;�UWN�����{�OC�����z�iKX��ڔN�dG�RCJY����i���y#>zS�MUc�������RORԾ�0�)�0��]:=Ϟ�t�����'\$�s�rF���67	=\$B��!qs	1\"���v��%��I�l<�b!ۮ6(Cd-�^<H`~2�K��zK�ٜ�Ա���y,qA�*�\0}��C�pb�\\�S�5����'(����|�M����W��5;\$5�T|��;k���t���@��;9�)��;i�.�;���_����F�=�D�M`H���\0�	 N @�%w��d��Pb�\$H|k�[��dCI!:l��,���<��u�t���NeϝW^�w�'6���D��f�u �ihI�Z:��~��ϣ�r���z�3�+�uoC�s2�b�ua�X��wWK�	HԶ27>�W���y����M�J��rpT��L��|`f��:���A�t�d|i��[w��j���W� 7���au�����e��A5�Q' ʐ\0��3�Ҿ\$����\rk)�a;���H=��֐~�IG�I�<���\"���I1'蠙�Gcm\0P\n�w��#�>���xB\"��Em|��2�\$}<3P�YX�go�d߶�<�����qE\"`���4�g�8r�]\n����:��qVb�T��m���9K&ғĤ�m�7)@��Qz���=��ߵű�H\n���}O�i}�\r٣.��v��p�JW&�u�55�0	�5��P�I��\n�������l\0O5*=��	�P-���H\0�f�%��tぺ*�S:�tϛ���?�ȂH����q4��K���@�Ԭ�܂.O(����Z�\$���]���o��n�z�A�!�t85<W�R2[�8���n5\$I��浕Z����]'}ET\n�����.���&�7��V�@�_�D�o��&J6��4i�j\$��EL���u��t����+I�Т���أ~�S�SZTX���PYz��\"\$V�_]�M(��7���������t_��S�����/��t���Ă���mH�:\0�5�- _Z'#���1�P��,�}(��~�\0��!Җ`-�P\ne�y (����`9O��!��;5�\n�\$�{������UA��7��!���[� �Y���F�濴�����>�8&����!CL���H����(�\0'Ǐ2��d\r%�;�k抐4��_O�>�5���@D�Ҽ��\0V�A�6' AY�����S�����rԾ�4�+h@b��������O�M\0���r̛�@�\rJ��m0\08�O���;k�Ӡ���A(6�|	`8 �\0��&��E�V��\0V�����wk�N��K����xdp���s�AL��A�X�k���u\0�����t �Ԣ�.�>(N��K'fld�A���?++��N��~������k�����PR\0��x������ʑ���BK]�bU��\\̛���d\0S@��Q��͉�b�\0\0b���\0_\\�@\nN���O�A��Pf��������ԏAj ��M4<�9���+�����`S�� ����w3T���7�X���T!\0e�PAI�b 1!\0��4���'� @�!�8\0��/���!:K�,�CAS�X�f�e��M��.:��:��t������._�d����81v`�B\"��!.^�*��N.^��\n�&\r(��.����O0��@��P��nj���ڗ#������&��rH�<��� �!��3��(i @�Aa��{� ¬#�S���6𨘶F@�����Y[O��(��.��/�B�����)L02B؈�-�ƀ��qp��J<�.Б\0\n��\0��/@8C�4P��\r	P�)��F���\$q.]�\"B#��	�#\\��84\$�s:.(*Oi>�|#T'`�Bu�a/���C��T�Ka�X8�`p�����\0`�\0");
	} elseif ($_GET["file"] == "jush.js") {
		header("Content-Type: text/javascript; charset=utf-8");
		echo
		lzw_decompress("v0��F����==��FS	��_6MƳ���r:�E�CI��o:�C��Xc��\r�؄J(:=�E���a28�x�?�'�i�SANN���xs�NB��Vl0���S	��Ul�(D|҄��P��>�E�㩶yHch��-3Eb�� �b��pE�p�9.����~\n�?Kb�iw|�`��d.�x8EN��!��2��3���\r���Y���y6GFmY�8o7\n\r�0��\0�Dbc�!�Q7Шd8���~��N)�Eг`�Ns��`�S)�O���/�<�x�9�o�����3n��2�!r�:;�+�9�CȨ���\n<�`��b�\\�?�`�4\r#`�<�Be�B#�N ��\r.D`��j�4���p�ar��㢺�>�8�\$�c��1�c���c����{n7����A�N�RLi\r1���!�(�j´�+��62�X�8+����.\r����!x���h�'��6S�\0R����O�\n��1(W0���7q��:N�E:68n+��մ5_(�s�\r��/m�6P�@�EQ���9\n�V-���\"�.:�J��8we�q�|؇�X�]��Y X�e�zW�� �7��Z1��hQf��u�j�4Z{p\\AU�J<��k��@�ɍ��@�}&���L7U�wuYh��2��@�u� P�7�A�h����3Û��XEͅZ�]�l�@Mplv�)� ��HW���y>�Y�-�Y��/�������hC�[*��F�#~�!�`�\r#0P�C˝�f������\\���^�%B<�\\�f�ޱ�����&/�O��L\\jF��jZ�1�\\:ƴ>�N��XaF�A�������f�h{\"s\n�64������?�8�^p�\"띰�ȸ\\�e(�P�N��q[g��r�&�}Ph���W��*��r_s�P�h���\n���om������#���.�\0@�pdW �\$Һ�Q۽Tl0� ��HdH�)��ۏ��)P���H�g��U����B�e\r�t:��\0)\"�t�,�����[�(D�O\nR8!�Ƭ֚��lA�V��4�h��Sq<��@}���gK�]���]�=90��'����wA<����a�~��W��D|A���2�X�U2��yŊ��=�p)�\0P	�s��n�3�r�f\0�F���v��G��I@�%���+��_I`����\r.��N���KI�[�ʖSJ���aUf�Sz���M��%��\"Q|9��Bc�a�q\0�8�#�<a��:z1Uf��>�Z�l������e5#U@iUG��n�%Ұs���;gxL�pP�?B��Q�\\�b��龒Q�=7�:��ݡQ�\r:�t�:y(� �\n�d)���\n�X;����CaA�\r���P�GH�!���@�9\n\nAl~H���V\ns��ի�Ư�bBr���������3�\r�P�%�ф\r}b/�Α\$�5�P�C�\"w�B_��U�gAt��夅�^Q��U���j����Bvh졄4�)��+�)<�j^�<L��4U*���Bg�����*n�ʖ�-����	9O\$��طzyM�3�\\9���.o�����E(i������7	tߚ�-&�\nj!\r��y�y�D1g���]��yR�7\"������~����)TZ0E9M�YZtXe!�f�@�{Ȭyl	8�;���R{��8�Į�e�+UL�'�F�1���8PE5-	�_!�7��[2�J��;�HR��ǹ�8p痲݇@��0,ծpsK0\r�4��\$sJ���4�DZ��I��'\$cL�R��MpY&����i�z3G�zҚJ%��P�-��[�/x�T�{p��z�C�v���:�V'�\\��KJa��M�&���Ӿ\"�e�o^Q+h^��iT��1�OR�l�,5[ݘ\$��)��jLƁU`�S�`Z^�|��r�=��n登��TU	1Hyk��t+\0v�D�\r	<��ƙ��jG���t�*3%k�YܲT*�|\"C��lhE�(�\r�8r��{��0����D�_��.6и�;����rBj�O'ۜ���>\$��`^6��9�#����4X��mh8:��c��0��;�/ԉ����;�\\'(��t�'+�����̷�^�]��N�v��#�,�v���O�i�ϖ�>��<S�A\\�\\��!�3*tl`�u�\0p'�7�P�9�bs�{�v�{��7�\"{��r�a�(�^��E����g��/���U�9g���/��`�\nL\n�)���(A�a�\" ���	�&�P��@O\n師0�(M&�FJ'�! �0�<�H�������*�|��*�OZ�m*n/b�/�������.��o\0��dn�)����i�:R���P2�m�\0/v�OX���Fʳψ���\"�����0�0�����0b��gj��\$�n�0}�	�@�=MƂ0n�P�/p�ot������.�̽�g\0�)o�\n0���\rF����b�i��o}\n�̯�	NQ�'�x�Fa�J���L������\r��\r����0��'��d	oep��4D��ʐ�q(~�� �\r�E��pr�QVFH�l��Kj���N&�j!�H`�_bh\r1���n!�Ɏ�z�����\\��\r���`V_k��\"\\ׂ'V��\0ʾ`AC������V�`\r%�����\r����k@N����B�횙� �!�\n�\0Z�6�\$d��,%�%la�H�\n�#�S\$!\$@��2���I\$r�{!��J�2H�ZM\\��hb,�'||cj~g�r�`�ļ�\$���+�A1�E���� <�L��\$�Y%-FD��d�L焳��\n@�bVf�;2_(��L�п��<%@ڜ,\"�d��N�er�\0�`��Z��4�'ld9-�#`��Ŗ����j6�ƣ�v���N�͐f��@܆�&�B\$�(�Z&���278I ��P\rk\\���2`�\rdLb@E��2`P( B'�����0�&��{���:��dB�1�^؉*\r\0c<K�|�5sZ�`���O3�5=@�5�C>@�W*	=\0N<g�6s67Sm7u?	{<&L�.3~D��\rŚ�x��),r�in�/��O\0o{0k�]3>m��1\0�I@�9T34+ԙ@e�GFMC�\rE3�Etm!�#1�D @�H(��n ��<g,V`R]@����3Cr7s~�GI�i@\0v��5\rV�'������P��\r�\$<b�%(�Dd��PW����b�fO �x\0�} ��lb�&�vj4�LS��ִԶ5&dsF M�4��\".H�M0�1uL�\"��/J`�{�����xǐYu*\"U.I53Q�3Q��J��g��5�s���&jь��u�٭ЪGQMTmGB�tl-c�*��\r��Z7���*hs/RUV����B�Nˈ�����Ԋ�i�Lk�.���t�龩�rYi���-S��3�\\�T�OM^�G>�ZQj���\"���i��MsS�S\$Ib	f���u����:�SB|i��Y¦��8	v�#�D�4`��.��^�H�M�_ռ�u��U�z`Z�J	e��@Ce��a�\"m�b�6ԯJR���T�?ԣXMZ��І��p����Qv�j�jV�{���C�\r��7�Tʞ� ��5{P��]�\r�?Q�AA������2񾠓V)Ji��-N99f�l Jm��;u�@�<F�Ѡ�e�j��Ħ�I�<+CW@�����Z�l�1�<2�iF�7`KG�~L&+N��YtWH飑w	����l��s'g��q+L�zbiz���Ţ�.Њ�zW�� �zd�W����(�y)v�E4,\0�\"d��\$B�{��!)1U�5bp#�}m=��@�w�	P\0�\r�����`O|���	�ɍ����Y��JՂ�E��Ou�_�\n`F`�}M�.#1��f�*�ա��  �z�uc���� xf�8kZR�s2ʂ-���Z2�+�ʷ�(�sU�cD�ѷ���X!��u�&-vP�ر\0'L�X �L����o	��>�Վ�\r@�P�\rxF��E��ȭ�%����=5N֜��?�7�N�Å�w�`�hX�98 �����q��z��d%6̂t�/������L��l��,�Ka�N~�����,�'�ǀM\rf9�w��!x��x[�ϑ�G�8;�xA��-I�&5\$�D\$���%��xѬ���´���]����&o�-3�9�L��z���y6�;u�zZ ��8�_�ɐx\0D?�X7����y�OY.#3�8��ǀ�e�Q�=؀*��G�wm ���Y�����]YOY�F���)�z#\$e��)�/�z?�z;����^��F�Zg�����������`^�e����#�������?��e��M��3u�偃0�>�\"?��@חXv�\"������*Ԣ\r6v~��OV~�&ר�^g���đٞ�'��f6:-Z~��O6;zx��;&!�+{9M�ٳd� \r,9����W��ݭ:�\r�ٜ��@睂+��]��-�[g��ۇ[s�[i��i�q��y��x�+�|7�{7�|w�}����E��W��Wk�|J؁��xm��q xwyj���#��e��(�������ߞþ��� {��ڏ�y���M���@��ɂ��Y�(g͚-����������J(���@�;�y�#S���Y��p@�%�s��o�9;�������+��	�;����ZNٯº��� k�V��u�[�x��|q��ON?���	�`u��6�|�|X����س|O�x!�:���ϗY]�����c���\r�h�9n�������8'������\rS.1��USȸ��X��+��z]ɵ��?����C�\r��\\����\$�`��)U�|ˤ|Ѩx'՜����<�̙e�|�ͳ����L���M�y�(ۧ�l�к�O]{Ѿ�FD���}�yu��Ē�,XL\\�x��;U��Wt�v��\\OxWJ9Ȓ�R5�WiMi[�K��f(\0�dĚ�迩�\r�M����7�;��������6�KʦI�\r���xv\r�V3���ɱ.��R������|��^2�^0߾\$�Q��[�D��ܣ�>1'^X~t�1\"6L���+��A��e�����I��~����@����pM>�m<��SK��-H���T76�SMfg�=��GPʰ�P�\r��>�����2Sb\$�C[���(�)��%Q#G`u��Gwp\rk�Ke�zhj��zi(��rO�������T=�7���~�4\"ef�~�d���V�Z���U�-�b'V�J�Z7���)T��8.<�RM�\$�����'�by�\n5����_��w����U�`ei޿J�b�g�u�S��?��`���+��� M�g�7`���\0�_�-���_��?�F�\0����X���[��J�8&~D#��{P���4ܗ��\"�\0��������@ғ��\0F ?*��^��w�О:���u��3xK�^�w���߯�y[Ԟ(���#�/zr_�g��?�\0?�1wMR&M���?�St�T]ݴG�:I����)��B�� v����1�<�t��6�:�W{���x:=��ޚ��:�!!\0x�����q&��0}z\"]��o�z���j�w�����6��J�P۞[\\ }��`S�\0�qHM�/7B��P���]FT��8S5�/I�\r�\n ��O�0aQ\n�>�2�j�;=ڬ�dA=�p�VL)X�\n¦`e\$�TƦQJ��k�7�*O�� .����ġ�\r���\$#p�WT>!��v|��}�נ.%��,;�������f*?�焘��\0��pD��! ��#:MRc��B/06���	7@\0V�vg����hZ\nR\"@��F	����+ʚ�E�I�\n8&2�bX�PĬ�ͤ=h[���+�ʉ\r:��F�\0:*��\r}#��!\"�c;hŦ/0��ޒ�Ej�����]�Z�����\0�@iW_���h�;�V��Rb��P%!��b]SB����Ul	����r��\r�-\0��\"�Q=�Ih����	 F���L��FxR�э@�\0*�j5���k\0�0'�	@El�O���H�Cx�@\"G41�`ϼP(G91��\0��\"f:Qʍ�@�`'�>7�Ȏ�d�����R41�>�rI�H�Gt\n�R�H	��bҏ��71���f�h)D��8�B`���(�V<Q�8c? 2���E�4j\0�9��\r�͐�@�\0'F�D��,�!��H�=�*��E�(���?Ѫ&xd_H�ǢE�6�~�u��G\0R�X��Z~P'U=���@����l+A�\n�h�IiƔ���PG�Z`\$�P������.�;�E�\0�}� ��Q�����%���jA�W�إ\$�!��3r1� {Ӊ%i=IfK�!�e\$���8�0!�h#\\�HF|�i8�tl\$���l����l�i*(�G���L	 �\$��x�.�q\"�Wzs{8d`&�W��\0&E����15�jW�b��ć��V�R����-#{\0�Xi���g*��7�VF3�`妏�p@��#7�	�0��[Ү���[�éh˖\\�o{���T���]��Ŧᑀ8l`f@�reh��\n��W2�*@\0�`K(�L�̷\0vT��\0�c'L����:�� 0��@L1�T0b��h�W�|\\�-���DN��\ns3��\"����`Ǣ�肒�2��&��\r�U+�^��R�eS�n�i0�u˚b	J����2s��p�s^n<���♱�Fl�a�\0���\0�mA2�`|؟6	��nr���\0Dټ��7�&m�ߧ-)���\\���݌\n=���;*���b��蓈�T��y7c��|o�/����:���t�P�<��Y:��K�&C��'G/�@��Q�*�8�v�/��&���W�6p.\0�u3����Bq:(eOP�p	�駲���\r���0�(ac>�N�|��	�t��\n6v�_��e�;y���6f���gQ;y�β[S�	��g�ǰ�O�ud�dH�H�=�Z\r�'���qC*�)����g��E�O�� \"��!k�('�`�\nkhT��*�s��5R�E�a\n#�!1�����\0�;��S�iȼ@(�l���I� �v\r�nj~��63��Έ�I:h����\n.��2pl�9Bt�0\$b��p+�ǀ*�tJ����s�JQ8;4P(��ҧѶ!��.Ppk@�)6�5��!�(��\n+��{`=��H,Ɂ\\Ѵ�4�\"[�C���1���-���luo��4�[���E�%�\"��w] �(� ʏTe��)�K�A�E={ \n�`;?���-�G�5I����.%�����q%E���s���gF��s	�����K�G��n4i/,�i0�u�x)73�Szg���V[��h�Dp'�L<TM��jP*o�≴�\nH���\n�4�M-W�N�A/@�8mH��Rp�t�p�V�=h*0��	�1;\0uG��T6�@s�\0)�6��ƣT�\\�(\"���U,�C:��5i�K�l���ۧ�E*�\"�r����.@jR�J�Q��/��L@�SZ���P�)(jj�J������L*���\0���\r�-��Q*�Qڜg��9�~P@���H���\n-e�\0�Qw%^ ET�< 2H�@޴�e�\0� e#;��I�T�l���+A+C*�Y���h/�D\\�!鬚8�»3�AЙ��E��E�/}0t�J|���1Qm��n%(�p��!\n��±U�)\rsEX���5u%B- ��w]�*��E�)<+��qyV�@�mFH ���BN#�]�YQ1��:��V#�\$������<&�X������x��t�@]G��Զ��j)-@�q��L\nc�I�Y?qC�\r�v(@��X\0Ov�<�R�3X���Q�J����9�9�lxCuīd�� vT�Zkl\r�J��\\o�&?�o6E�q������\r���'3��ɪ�J�6�'Y@�6�FZ50�V�T�y���C`\0��VS!���&�6�6���rD�f`ꛨJvqz���F�����@�ݵ��҅Z.\$kXkJ�\\�\"�\"�֝i��:�E���\roX�\0>P��P�mi]\0�����aV��=���I6�����jK3���Z�Q�m�E���b�0:�32�V4N6����!�l�^ڦ�@h�hU��>:�	��E�>j�����0g�\\|�Sh�7y�ބ�\$��,5aė7&��:[WX4��q� ���J���ׂ�c8!�H���VD�Ď�+�D�:����9,DUa!�X\$��Я�ڋG�܌�B�t9-+o�t��L��}ĭ�qK��x6&��%x��tR�����\"�π�R�IWA`c���}l6��~�*�0vk�p���6��8z+�q�X��w*�E��IN�����*qPKFO\0�,�(��|�����k *YF5���;�<6�@�QU�\"��\rb�OAXÎv��v�)H��o`ST�pbj1+ŋ�e��� ʀQx8@�����5\\Q�,���ĉN��ޘb#Y�H��p1����kB�8N�o�X3,#Uک�'�\"�销�eeH#z��q^rG[��:�\r�m�ng����5��V�]��-(�W�0���~kh\\��Z��`��l����k �o�j�W�!�.�hF���[t�A�w�e�M૫��3!����nK_SF�j���-S�[r�̀w��0^�h�f�-����?���X�5�/������IY �V7�a�d �8�bq��b�n\n1YR�vT���,�+!����N�T��2I�߷�����������K`K\"�����O)\nY��4!}K�^����D@��na�\$@� ��\$A��j����\\�D[=�	bHp�SOAG�ho!F@l�U��`Xn\$\\�͈_��˘`���HB��]�2���\"z0i1�\\�����w�.�fy޻K)����� p�0���X�S>1	*,]��\r\"���<cQ��\$t��q��.��	<��+t,�]L�!�{�g���X��\$��6v����� ����%G�H������E����X��*��0ۊ)q�nC�)I���\"�����툳�`�KF����@�d�5��A��p�{�\\���pɾN�r�'�S(+5�Њ+�\"�Ā�U0�iː����!nM��brK���6ú�r���|a����@�x|��ka�9WR4\"?�5��p�ۓ��k�rĘ����ߒ����7Hp��5�YpW���G#�rʶAWD+`��=�\"�}�@H�\\�p���Ѐ�ߋ�)C3�!�sO:)��_F/\r4���<A��\nn�/T�3f7P1�6����OYлϲ���q��;�؁���a�XtS<��9�nws�x@1Ξxs�?��3Ş@���54��o�ȃ0����pR\0���������yq��L&S^:��Q�>\\4OIn��Z�n��v�3�3�+P��L(�������.x�\$�«C��Cn�A�k�c:L�6���r�w���h����nr�Z��=�=j�ђ���6}M�G�u~�3���bg4���s6s�Q��#:�3g~v3���<�+�<���a}ϧ=�e�8�'n)ӞcC�z��4L=h��{i����J�^~��wg�D�jL���^����=6ΧN�Ӕ����\\��D���N���E�?h�:S�*>��+�u�hh҅�W�E1j�x������t�'�t�[��wS���9��T��[�,�j�v����t��A#T���枂9��j�K-��ޠ���Y�i�Qe?��4Ӟ���_Wz����@JkWY�h��pu����j|z4���	�i��m�	�O5�\0>�|�9�ז��轠��gVy��u���=}gs_���V�sծ{�k�@r�^���(�w����H'��a�=i��N�4����_{�6�tϨ��ϗe�[�h-��Ul?J��0O\0^�Hl�\0.��Z������xu���\"<	�/7���� ���i:��\nǠ���;��!�3���_0�`�\0H`���2\0��H�#h�[�P<����עg����m@~�(��\0ߵk�Y�v���#>���\nz\n�@�Q�\n(�G��\n����'k����5�n�5ۨ�@_`Ї_l�1���wp�P�w���\0��c��oEl{�ݾ�7����o0����Ibϝ�n�z����﷛� ���{�8�w�=��|�/y�3a�߼#xq����@��ka�!�\08d�m��R[wvǋRGp8���v�\$Z���m��t��������������ǽ����u�o�p�`2��m|;#x�m�n�~;��V�E�������3O�\r�,~o�w[��N��}�� �cly��O����;��?�~�^j\"�Wz�:�'xW��.�	�u�(��Ý�q��<g��v�hWq��\\;ߟ8��)M\\��5vڷx=h�i�b-���|b���py�DЕHh\rce��y7�p��x��G�@D=� ����1��!4Ra\r�9�!\0'�Y����@>iS>����o��o��fsO 9�.����\"�F��l��20��E!Q���ːD9d�BW4��\0��y`RoF>F�a��0�����0	�2�<�I�P'�\\���I�\0\$��\n R�aU�.�sЄ��\"���1І�e�Y砢�Z�q��1�|��#�G!�P�P\0|�H�Fnp>W�:��`YP%�ď�\n�a8��P>�����`]��4�`<�r\0�Î������z�4����8�����4�`m�h:�Ϊ�HD���j�+p>*����8�ՠ0�8�A��:���с�]w�ú�z>9\n+�������:����ii�PoG0���1��)�Z�ږ�n�����eR֖��g�M�����gs�LC�r�8Ѐ�!�����3R)��0�0��s�I��J�VPpK\n|9e[���ˑ��D0����z4ϑ�o������,N8n��s�#{蓷z3�>�BS�\";�e5VD0���[\$7z0������=8�	T 3���Q�'R������n��L�yŋ��'�\0o��,��\0:[}(���|���X�>xvqW�?tB�E1wG;�!�݋5΀|�0��JI@��#���uņI��\\p8�!'�]߮��l-�l�S�B��,ӗ���]��1�ԕH��N�8%%�	��/�;�FGS���h�\\ل�c�t����2|�W�\$t��<�h�O��+#�B�aN1��{��y�w���2�\\Z&)�d�b'��,Xxm�~�H��@:d	>=-��lK��܏�J�\0���́�@�rϥ�@\"�(A����Z�7�h>����\\����#>���\0��Xr�Y��Yxŝ�q=:��Թ�\rl�o�m�gb��������D_�Tx�C���0.��y��R]�_���Z�ǻW�I��G��	Mɪ(��|@\0SO��s� {��@k}��FXS�b8��=��_����l�\0�=�g��{�H��yG���� s�_�J\$hk�F�q������d4ω����'���>vϏ��!_7�Vq��@1z�uSe��jKdyu���S�.�2�\"�{��K���?�s��˦h��R�d��`:y����Gھ\nQ�����ow��'��hS��>���L�X}��e���G��@9��퟈�W�|��Ϲ�@�_��uZ=��,���!}���\0�I@��#��\"�'�Y`��\\?��p��,G����ל_��'�G����	�T��#�o��H\r��\"���o�}��?��O鼔7�|'���=8�M��Q�y�a�H�?��߮� ���\0���bUd�67���I O����\"-�2_�0�\r�?�������hO׿�t\0\0002�~�° 4���K,��oh��	Pc���z`@��\"�����H; ,=��'S�.b��S����Cc���욌�R,~��X�@ '��8Z0�&�(np<pȣ�32(��.@R3��@^\r�+�@�,���\$	ϟ��E���t�B,���⪀ʰh\r�><6]#���;��C�.Ҏ����8�P�3��;@��L,+>���p(#�-�f1�z���,8�ߠ��ƐP�:9����R�۳����)e\0ڢR��!�\nr{��e����GA@*��n�D��6��������N�\r�R���8QK�0��颽��>PN���IQ=r<�;&��f�NGJ;�UA�����A�P�&������`�����);��!�s\0���p�p\r�����n(��@�%&	S�dY����uC�,��8O�#�����o���R�v,��#�|7�\"Cp����B�`�j�X3�~R�@��v�����9B#���@\n�0�>T�����-�5��/�=� ���E����\n��d\"!�;��p*n��Z�\08/�jX�\r��>F	Pϐe>��O��L����O0�\0�)�k���㦃[	��ϳ���'L��	����1 1\0��C�1T�`����Rʐz�Ě����p��������< .�>�5��\0���>� Bnˊ<\"he�>к�î��s�!�H�{ܐ�!\r�\r�\"��|��>R�1d���\"U@�D6����3���>o\r����v�L:K�2�+�0쾁�>��\0�� ���B�{!r*H��y;�`8\0��د��d����\r�0���2A����?��+�\0�Å\0A����wS��l����\r[ԡ�6�co�=����0�z/J+�ꆌ�W[��~C0��e�30HQP�DPY�}�4#YD���p)	�|�@���&�-��/F�	�T�	����aH5�#��H.�A>��0;.���Y�ġ	�*�D2�=3�	pBnuDw\n�!�z�C�Q \0��HQ4D�*��7\0�J��%ıp�uD�(�O=!�>�u,7��1��TM��+�3�1:\"P�����RQ?���P���+�11= �M\$Z��lT7�,Nq%E!�S�2�&��U*>GDS&����ozh8881\\:��Z0h���T �C+#ʱA%��D!\0�����XDA�3\0�!\\�#�h���9b��T�!d�����Y�j2��S����\nA+ͽ��H�wD`�(AB*��+%�E��X.ˠB�#��ȿ��&��Xe�Eo�\"��|�r��8�W�2�@8Da�|�������N�h����J8[�۳����W�z�{Z\"L\0�\0��Ȇ8�x�۶X@�� �E����h;�af��1��;n��hZ3�E����0|� 옑��A���t�B,~�W�8^�Ǡ׃��<2/	�8�+��۔���O+�%P#ή\n?�߉?��e˔�O\\]�7(#��D۾�(!c)�N����MF�E�#DX�g�)�0�A�\0�:�rB��``  ��Q��H>!\rB��\0��V%ce�HFH��m2�B�2I����`#���D>���n\n:L���9C���0��\0��x(ޏ�(\n����L�\"G�\n@���`[���\ni'\0��)������y)&��(p\0�N�	�\"��N:8��.\r!��'4|ל~����ʀ���\"�c��Dlt����0c��5kQQר+�Z��Gk�!F��c�4��Rx@�&>z=��\$(?���(\n쀨>�	�ҵ���Cqی��t-}�G,t�GW �xq�Hf�b\0�\0z���T9zwЅ�Dmn'�ccb�H\0z���3�!����� H��Hz׀�Iy\",�-�\0�\"<�2���'�#H`�d-�#cl�jĞ`��i(�_���dgȎ�ǂ*�j\r�\0�>� 6���6�2�kj�<�Cq��9�Đ��I\r\$C�AI\$x\r�H��7�8 ܀Z�pZrR����_�U\0�l\r��IR�Xi\0<����r�~�x�S��%��^�%j@^��T3�3ɀGH�z��&\$�(��q\0��f&8+�\rɗ%�2hC�x���I��lbɀ�(h�S�Y&��B������`�f��x�v�n.L+��/\"=I�0�d�\$4�7r����A���(4�2gJ(D��=F�����(����-'Ġ�XG�2�9Z=���,��r`);x\"��8;��>�&�����',�@��2�pl���:0�lI��\rr�JD���������hA�z22p�`O2h��8H��Ąwt�BF���g`7���2{�,Kl���߰%C%�om���������+X����41򹸎\n�2p��	ZB!�=V�ܨ�Ȁ�+H6���*��\0�k���%<� �K',3�r�I�;��8\0Z�+Eܭ�`������+l����W+�Yҵ-t��f�b�Q��_-Ӏޅ�+�� 95�LjJ.Gʩ,\\��ԅ.\$�2�J�\\�-��1�-c���ˇ.l�f�xBqK�,d��ˀ�8�A�Ko-��������3K��r��/|����/\\�r���,��HϤ�!�Y�1�0�@�.�&|����+��J\0�0P3J�-ZQ�	�\r&����\n�L�*���j�ĉ|�����#Ծ�\"˺���A��/���8�)1#�7\$\"�6\n>\n���7L�1���h9�\0�B�Z�d�#�b:\0+A���22��'̕\nt���̜�O��2lʳ.L��HC\0��2���+L�\\��r�Kk+���˳.ꌒ�;(Dƀ���1s����d�s9�����P4�쌜��@�.���A��nhJ�1�3�K�0��3J\$\0��2�Lk3��Q�;3��n\0\0�,�sI�@��u/VA�1���UM�<�Le4D�2��V�% �Ap\nȬ2��35���A-��T�u5�3�۹1+fL~�\n���	��->�� �ҡM�4XL�S��dٲ�͟*\\�@ͨ��Y�k����SDM�5 Xf����D�s���Us%	�̱p+K�6��/���ݒ�8X�ނ=K�6pH����%�3�ͫ7l�I�K0���L��D��u���`��P\r��SO͙&(;�L@��ψN>S��2��8(���`J�E��r�F	2��SE��M��M��\$q�E��\$�ã/I\$\\���ID�\"��\n䱺�w.t�S	���ђP��#\nW��-\0Cҵ�:j�R��^S���8;d�`���5Ԫ�aʖ��E��+(Xr�M�;��3�;���B,��*1&����2X�S���)<� �L9;�RSN����gIs+��ӰK�<��s�LY-Z�:A<���OO*��2v�W7��+|���˻<T���9�h����y\$<��#ρ;����v�\$��O�\0� �,Hk��-���Ϛ\r����ϣ;���O�>�����7>��3@O{.4�pO�?T�b���.�.~O�4��S���>1SS��*4�Pȣ�>�����3�\0�W�>��2��><���P?4��@��t\nN����A�xp��%=P@��C�@�R�˟?x��\n���0N�w�O?�TJC@��#�	.d���M��t�&=�\\�4��A��:L����\$���N��:��\r��I'���A�rግ;\r�/��C���B�Ӯ�i>L��7:9�����|�C\$��)�����z@�tl�:>��C�\n�Bi0G��,\0�FD%p)�o\0����\n>��`)QZI�KG�%M\0#\0�D���Q.H�'\$�E\n �\$ܐ%4I�D�3o�:L�\$��m ��0�	�B�\\(����8��通�h��D��C�sDX4TK���{��x�`\n�,��\nE��:�p\n�'��>��o\0���tI��` -\0�D��/��KP�`/���H�\$\n=���>��U�FP0���UG}4B\$?E����%�T�WD} *�H0�T�\0t������\"!o\0�E�7��R.���tfRFu!ԐD�\n�\0�F-4V�QH�%4��0uN\0�D�QRuE�	)��I\n�&Q�m�)ǚ�m �#\\����D��(\$̓x4��WFM&ԜR5H�%q��[F�+���IF \nT�R3D�L�o���y4TQ/E��[ў<�t^��F��)Q��+4�Q�I�#���IF�'TiѪX��!ѱF�*�nR�>�5�p��Km+�s��������I���R�E�+ԩ��M\0��(R�?�+HҀ�J�\"T�D���\$���	4wQ�}Tz\0�G�8|�x���R��6�R�	4XR6\n�4y�mN��Q�NM�&R�H&�2Q/�7#�қ�{�'�ҍ,|����\n�	.�\0�>�{�o#1D�;��?U��ҕJ�9�*����j����F�N��щJ� #�~%-?C���L�3�@EP�{`>Q�Ȕ��%O�)4�R%I�@��%,�\"���I�<�����\$ԉTP>�\n�\0QP5D��kOF�TY�<�o�Q�=T�\0��x	5�D�,�0?�i�?x�  �mE}>�|����[��\0����&RL���H�S9�G�I��1䀖��M4V�H�oT-S�)Q�G�F [��TQRjN��#x]N(�U�8\nuU\n?5,TmԞ?����?��@�U\n�u-��R�9��U/S \nU3�IESt�QYJu.�Q��F�o\$&���i	��KPC�6�>�5�G\0uR��u)U'R�0�Ѐ�DuIU�J@	��:�V8*�Rf%&�\\�R��MU9R��fUAU[T�UQSe[��\0�KeZUa��Uh��mS<���,R�s�`&Tj@��G�!\\x�^�0>��\0&��p�΂Q�Q�)T�U�Ps�@%\0�W�	`\$��(1�Q?�\$C�Qp\n�O�J��X�#��V7X�u;�!YB��S�c��+V����#MU�W�H��U�R�ǅU-+��VmY}\\���OK�M��\$�S�eToV���HT��!!<{�R��ZA5�R�!=3U��(�{@*Ratz\0)Q�P5H؏���հ�N5+���P�[��9�V%\"����\n����G�SL�����9�����l����\rV�ؤ�[�ou�UIY�R_T�Y�p5O֧\\�q`�U�[�Bu'Uw\\mRU�ԭ\\Es5�K\\���V�\\�S�{�AZ%O��\$��F���>�5E�WVm`��Wd]& \$�Ό����!R�Z}ԅ]}v5���ZUg��Q^y` �!^=F��R�^�v�U�Kex@+��r5�#�@?=�u�Γs���ץY�N�sS!^c�5�\$.�u`��\0�XE~1�9��J�UZ�@�#1_[�4J�2�\n�\$VI�4n�\0�?�4a�R�!U~)&��B>t�R�I�0��_EkTUS��|��Uk_�8�&��E��(‘?�@���J�5���JU�BQT}HV��j��Qx\ne�VsU=���V�N�4ղؗ\\x����R34�G�D\":	KQ�>�[�\r�Y_�#!�#][j<6خX	���c���#KL}>`'\0��5�X�cU�[\0��(���Wt|t�R]p�/�]H2I�QO��1�S�Qj�Z����H���m���)d�^SXCY\r�tu@J�p��%��M������?�UQ�\n�=R�ar:ԿE���-G�\0\$��d���]�meh*��Q�Wt��c��`��A�Y=S\r���	m-���=Mw�H�]J�\"䴏������f�\"�{#9Te����M�c��N�I����D������U�6��g��2��ݝ�e�a�L��Q&&uT�X�51Y�>����S�֊Q#�I���j�\0����W�P��?ub5FU�Ln�)V5R�@��\$!%o��P��'��E�U��P�-����B�p\n�F\$�S4�t�UF|{�q�ȓ0���Umjs�������\$�ڛj��c�ڐ��֫��aZI5X��j�26��&>v��\n\r)2�_k�G��TJ��eQ-c�Z�VM�ֽ�z>�]�a�c��c��`t��H��j�6��+k�M�\0�>���##3l=�'���^6�\0�èv�Z9Se��\"���bΡ�B>�)�/T�=�9\0�`P�\$\0�]�/0ڪ��䵏�k-�6��{k���[�F\r|�SѿJ��MQ�D=�/�WX���V�a�'���a�to��l冶�Xj}C@\"�KP����om�3\0#HV���v��~�{���?gx	n|[�?U��[r�h��G�`�3#Gk%L��\0�I�`C�D��	 \"\0��ŧ��#cN�6�ڹf���zێ�;Ѥ�eeF�7�/N\r:��Q�G�9	\$��I�ռ��]��T��WGs��dW�M�I����f�Bc�ۤ����!#cnu&(�S�_�w��Sf�&T�Z:��0C�S�LN`ܳYj=��>Ų��Z!=�rV]g��	ӣr���Xl��-.�U�'uJuJ\0�s�J�'W%���\\>?�B��V�j4���J}I/-ҝrRL�S�3\0,Rgqӭ��Tf>�1��\0�_���\\V8��Z�t��c耆�<^\\�ll�j\0���T�]C��w�ΓzI��ZwN���pVW�jv�Y�>�2�	o\$|U�W�L%{toX3_���R�J5~6\"��Zl}�`�kc����eR=^UԎ��1�ѽw7e�d��v��b�=��\0�f��,��m�)��Gp��-Ӽ�)9L���>|�� \"�@���5�`�:��\0�,��t@��x���l�J���b�6������a��A\0ػAR�[A���0\$qo�A��S��@���<@�y��\"as.����V^��讥^�����\0��H���[H@�bK����)z�\r����=��^�z�B\0�����N�o<̇t<�x�\0ڬ0*R��I{����^�E�:�{KՐ�1E�0��Y����/��c��\"\0��4���F�7'���\n�0��`U�T��?MP���l��4��r(	��Z�|���&��t\"I����L�w+�m}����Wi\r>�U__u��63�y[�8�T-��V�}�x��_~�%�7��{jM�o_�E�����~]�P\$�J�CaXG�9�\0007Ń5�A#�\0.���\r˴��_������%����\n�\r#<M�x�J���|��2�\0��;o�^a+F����笀Lk��;�_���#��M\\����pr@��õ�����OR���~z��A�NE�Y�O	(1N׉�R��8��C�����n?O)��1�A�Do\0�\r�Ǣ?�kJ��\"�,�OF��a����-b�6]PS�)ƙ�5xC�=@j����L�����L�:\"胻Ί�l#���B�k��������@��N��:�>�|B����9�	���:N��\$��S� �CB:j6����ΉJk��uK�_�W�͢ØI�=@Tv��\n0^o�\\�Ӡ?/��&u�.��_��\r��C��+��c�~�J�b�6���e\0�y�ѡ\0wx�h��8j%S���VH@N'�\\ۯ��N�`n\r��u�n�K�qU�B�+�f>G��\r���=@G���d���\n�)��FO� hʷ��ÈfC�ɅX|��I�]��3auy�Ui^�9y�\no^rt\r8��͇#����N	V��Y�;�c*�%V�<��#�h9r�\rxc�v(\ra���(xja�`g�0�V̼���Q��x(���glհ{��gh`sW<Kj�'�;)�Gnq\$�p�+�Ɍ_��d��^& ���D�x�!b�v�!EjPV�'����(�=�b�\r�\"�b��L�\0���bt�\n>J���1;�����ۈ�4^s�Q�p`�fr`7���x��E<l���	8s��'PT��ֺ�˃��z_�T[>��:��`�1.���;7�@��[��>��6!�*\$`��\0���`,�������@����?�m�>�>\0�LCǸ�R��n��/+�`;C����\0�*�<F���+���q M���;1�K\n�:b�3j1��l�:c>�Y���h���ގ�#�;���3ֺ�8�5�:�\\��\0XH���a�����M1�\\�L[YC��vN��\0+\0��t#�\$�����!@*�l��	F�dhd���F���&��Ƙf�)=��0��4�x\0004ED�6K��䢣���\0�nN�];q�4sj-�=-8���\0�sǨ���D�f5p4����J�^���'Ӕ[��H^�NR F�Kw�z�� ��E����gF|!�c���o�db����x�\0�-��6�,E��_���3u�p ��/�wz�(��ex�Ra�H�Y�ce��5�9d\0�0@2@Ґ�Y�fey��Y�cMו�h����[�ez\rv\\0�e���\\�cʃ��[�ue��NY`��ۖ�]9h姗~^Yqe���]�qe_|6!���u�`�f��J�{�7��M{�Yه��j�e��C��S6\0DuasFL}�\$ȇ�(��Mb���Ƥ,0Buί���т2�gxFљ{�a�n:i\rPj�e��r�r��G�BY��M+q��iY�d˙�`0��,>6�fo�0���o�� �Xf����\0�V�L!��f��l��6� �/��1e��\0�>kbf�\r�!�uf�<%�(r˛�a&	����Y��!���mBg=@��\r�; \r�5phI�9bm�\$BYˋ���g�x�#�@QEO��m9���0\"���!�t���ˉ��Ї�O* ���\0��>%�\$�o�rN&s9�f��4���g��~jM�f�wy�g�y�\\`X1y5x����^z�_,& k���|����1x��A�6� \n�o蔻�&x��gg�{r�?緛�-����|t�3�����}gHgK�9����J�<C�C��1��9�7��g����h6!0H���cdy�f��DA;��9�T���0��\0�p�����!� 6^�.�S²?���E(P�Έ .���5��h���EPJv��.���+�\$�5��>P+�?~��g�6\r��h��p�z(�W��`��\"y���:�FadŬ�6:��f��i\0����A;�e�����^��w�f� >y�����`-\r����\0�hr\r�r�8i\"_�	����9�CI��fXˈ2���\"�Ţ����h�L~�\"���%V�:!%��xy�izyg�vx�]���}qg����Zi��|��`�+ _�g�����٣������譞6PA�ʀ\$�=�9�����h��|p��������!��.�!�����i�^���iˢ�8zVC����Z\"����(�����9�U)��!DgU\0�j��?`��4�LTo@�B����N�a�{�r�:\n̟�E��8æ&=�E�*Z:\n?��g���̊��h��.����N�5(�S�h��i2�*c�f�@����7��z\"�|��rP�.ǀ�L8T'��k���:(�q2&��ED�2~���ر�����9���v���8������@��^X=X`��qZ��Q�֮`9j�5^���@竸�n�qv����3����(I6�j�dT���\\� ��3�,��h�k�3�(�3���P�u�V�|\0阮U�k;��JQ���.��	:J\r��1��n�BI\r\0ɬh@��?�N�\nsh���\"��;�r~7O�\$��(�5�R���	�ʽj����FYF��ܔ��~�x޾�f��\"�vۓo��˨��º#��a�����P���<��h�-3麝/G�x����n�i@\"�G�?��,�Zp�xX`v�4X������[�I��7�åXc	��!�b�}�j�_��9�5qti�6f������ٞ5���Fƹ�iѱ�pX'�2��r���0�ƺ��D,#G�U2��؏�I��\rl(�� �챣��=�A�a�쩳-8�dbS����4~���H;���0�6��b��{��޺R���s3z�����N�ބ��`�ˆ+���4<�^a�y���	}r���y������k�&4@��?~���cE����@�LS@���z^�qqN��</H�j^sC�`��sbgGy����^\n�N�\n:G�N}�c\n����� +���=�p�1��N�TB[d������Ћ��ܹ�`�n�oj;�jěwh����c9��p̡[y4���05�͋N��+ο��`Xda��/zn*�P�����#t�赸~�9W�	�V��~=�#��n)����	2��;�j:��J�k�C�!>x��5��==�2���.��|�'���[��'�;��v�������������;:SA	�&�[�me���n������˵���<��6ma�=Y.神��:g����腀����;�I߻x�[��I�J\0�~�zaY������wT\\`��V\n�~P)�zJ�������Q@��[�{rʉ�D�B�v��|i-�E��K�;^n�{���:Nh;���2��ƀp�Ѵ6����罘9�9����X�hQ�~���iA�@D �j���}�ozLV���ѳ~���	8B?�#F}F�Td����e��zc��F���g�7Η���� 6�#.E£����£��S�.J3��5��Kɥ�J���;���n5��:yS��C�voս.�{��	d\\0�?W\0!)�'����Eg�;�+��\0�Y�Nt�bp+��c�����\0�B=\"�c�T�:B������c��������P�I��D��V0��!ROl�O�N~aF�|%�ߺ�����)O��	�W�o����Q�w��:ٟl�0h@:���օ8�Q�&�[�n�F��p,�æ�@��JT�w�9��(���<�{�ƐO\r�	���ڂ\$m�/HnP\$o^�U��\"���{Ė�<.���n�q8\r�\0;�n������硟�+�޳3��n{�D\$7�,Ez7\0��l!{��8��x҂�.s8�PA�Fx�r����Qۮ���1̅�p+@�d��9OP5�lK�/�����\\m����s�q���v�Q�/���	�!���z�7�o��Eǆ�:q�V�5�?G�HO��O�\$�l��+�,�\r;�����~�Ač錳�{�`7|��Ă���r'��Ji\rc+�|�#+<&қ�<W,��>��^�P�&n�Jh�e�%d������C�i�zX�A�'D�>��Έ�Ek���@�B�w(�.��\n99A�hN�c�kN��d`���p`��%2���\0");
	} else {
		header("Content-Type: image/gif");
		switch ($_GET["file"]) {
			case "plus.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0!�����M��*)�o��) q��e���#��L�\0;";
				break;
			case "cross.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0#�����#\na�Fo~y�.�_wa��1�J�G�L�6]\0\0;";
				break;
			case "up.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����MQN\n�}��a8�y�aŶ�\0��\0;";
				break;
			case "down.gif":
				echo "GIF89a\0\0�\0001���\0\0����\0\0\0!�\0\0\0,\0\0\0\0\0\0 �����M��*)�[W�\\��L&ٜƶ�\0��\0;";
				break;
			case "arrow.gif":
				echo "GIF89a\0\n\0�\0\0������!�\0\0\0,\0\0\0\0\0\n\0\0�i������Ӳ޻\0\0;";
				break;
		}
	}
	exit;
}
if ($_GET["script"] == "version") {
	$q = file_open_lock(get_temp_dir() . "/adminer.version");
	if ($q) file_write_unlock($q, serialize(array("signature" => $_POST["signature"], "version" => $_POST["version"])));
	exit;
}
global $c, $g, $l, $Kb, $Rb, $bc, $m, $Gc, $Lc, $ba, $dd, $y, $a, $vd, $re, $We, $mg, $Qc, $T, $Ug, $ah, $hh, $fa;
if (!$_SERVER["REQUEST_URI"]) $_SERVER["REQUEST_URI"] = $_SERVER["ORIG_PATH_INFO"];
if (!strpos($_SERVER["REQUEST_URI"], '?') && $_SERVER["QUERY_STRING"] != "") $_SERVER["REQUEST_URI"] .= "?$_SERVER[QUERY_STRING]";
if ($_SERVER["HTTP_X_FORWARDED_PREFIX"]) $_SERVER["REQUEST_URI"] = $_SERVER["HTTP_X_FORWARDED_PREFIX"] . $_SERVER["REQUEST_URI"];
$ba = ($_SERVER["HTTPS"] && strcasecmp($_SERVER["HTTPS"], "off")) || ini_bool("session.cookie_secure");
@ini_set("session.use_trans_sid", false);
if (!defined("SID")) {
	session_cache_limiter("");
	session_name("adminer_sid");
	$Me = array(0, preg_replace('~\?.*~', '', $_SERVER["REQUEST_URI"]), "", $ba);
	if (version_compare(PHP_VERSION, '5.2.0') >= 0) $Me[] = true;
	call_user_func_array('session_set_cookie_params', $Me);
	session_start();
}
remove_slashes(array(&$_GET, &$_POST, &$_COOKIE), $wc);
if (function_exists("get_magic_quotes_runtime") && get_magic_quotes_runtime()) set_magic_quotes_runtime(false);
@set_time_limit(0);
@ini_set("zend.ze1_compatibility_mode", false);
@ini_set("precision", 15);
$vd = array('en' => 'English', 'ar' => 'العربية', 'bg' => 'Български', 'bn' => 'বাংলা', 'bs' => 'Bosanski', 'ca' => 'Català', 'cs' => 'Čeština', 'da' => 'Dansk', 'de' => 'Deutsch', 'el' => 'Ελληνικά', 'es' => 'Español', 'et' => 'Eesti', 'fa' => 'فارسی', 'fi' => 'Suomi', 'fr' => 'Français', 'gl' => 'Galego', 'he' => 'עברית', 'hu' => 'Magyar', 'id' => 'Bahasa Indonesia', 'it' => 'Italiano', 'ja' => '日本語', 'ka' => 'ქართული', 'ko' => '한국어', 'lt' => 'Lietuvių', 'ms' => 'Bahasa Melayu', 'nl' => 'Nederlands', 'no' => 'Norsk', 'pl' => 'Polski', 'pt' => 'Português', 'pt-br' => 'Português (Brazil)', 'ro' => 'Limba Română', 'ru' => 'Русский', 'sk' => 'Slovenčina', 'sl' => 'Slovenski', 'sr' => 'Српски', 'sv' => 'Svenska', 'ta' => 'த‌மிழ்', 'th' => 'ภาษาไทย', 'tr' => 'Türkçe', 'uk' => 'Українська', 'vi' => 'Tiếng Việt', 'zh' => '简体中文', 'zh-tw' => '繁體中文',);
function
get_lang()
{
	global $a;
	return $a;
}
function
lang($v, $ie = null)
{
	if (is_string($v)) {
		$Ze = array_search($v, get_translations("en"));
		if ($Ze !== false) $v = $Ze;
	}
	global $a, $Ug;
	$Tg = ($Ug[$v] ? $Ug[$v] : $v);
	if (is_array($Tg)) {
		$Ze = ($ie == 1 ? 0 : ($a == 'cs' || $a == 'sk' ? ($ie && $ie < 5 ? 1 : 2) : ($a == 'fr' ? (!$ie ? 0 : 1) : ($a == 'pl' ? ($ie % 10 > 1 && $ie % 10 < 5 && $ie / 10 % 10 != 1 ? 1 : 2) : ($a == 'sl' ? ($ie % 100 == 1 ? 0 : ($ie % 100 == 2 ? 1 : ($ie % 100 == 3 || $ie % 100 == 4 ? 2 : 3))) : ($a == 'lt' ? ($ie % 10 == 1 && $ie % 100 != 11 ? 0 : ($ie % 10 > 1 && $ie / 10 % 10 != 1 ? 1 : 2)) : ($a == 'bs' || $a == 'ru' || $a == 'sr' || $a == 'uk' ? ($ie % 10 == 1 && $ie % 100 != 11 ? 0 : ($ie % 10 > 1 && $ie % 10 < 5 && $ie / 10 % 10 != 1 ? 1 : 2)) : 1)))))));
		$Tg = $Tg[$Ze];
	}
	$ta = func_get_args();
	array_shift($ta);
	$Cc = str_replace("%d", "%s", $Tg);
	if ($Cc != $Tg) $ta[0] = format_number($ie);
	return
		vsprintf($Cc, $ta);
}
function
switch_lang()
{
	global $a, $vd;
	echo "<form action='' method='post'>\n<div id='lang'>", lang(19) . ": " . html_select("lang", $vd, $a, "this.form.submit();"), " <input type='submit' value='" . lang(20) . "' class='hidden'>\n", "<input type='hidden' name='token' value='" . get_token() . "'>\n";
	echo "</div>\n</form>\n";
}
if (isset($_POST["lang"]) && verify_token()) {
	cookie("adminer_lang", $_POST["lang"]);
	$_SESSION["lang"] = $_POST["lang"];
	$_SESSION["translations"] = array();
	redirect(remove_from_uri());
}
$a = "en";
if (isset($vd[$_COOKIE["adminer_lang"]])) {
	cookie("adminer_lang", $_COOKIE["adminer_lang"]);
	$a = $_COOKIE["adminer_lang"];
} elseif (isset($vd[$_SESSION["lang"]])) $a = $_SESSION["lang"];
else {
	$ka = array();
	preg_match_all('~([-a-z]+)(;q=([0-9.]+))?~', str_replace("_", "-", strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"])), $Jd, PREG_SET_ORDER);
	foreach ($Jd
		as $C) $ka[$C[1]] = (isset($C[3]) ? $C[3] : 1);
	arsort($ka);
	foreach ($ka
		as $z => $H) {
		if (isset($vd[$z])) {
			$a = $z;
			break;
		}
		$z = preg_replace('~-.*~', '', $z);
		if (!isset($ka[$z]) && isset($vd[$z])) {
			$a = $z;
			break;
		}
	}
}
$Ug = $_SESSION["translations"];
if ($_SESSION["translations_version"] != 1579331192) {
	$Ug = array();
	$_SESSION["translations_version"] = 1579331192;
}
function
get_translations($ud)
{
	switch ($ud) {
		case "en":
			$f = "A9D�y�@s:�G�(�ff�����	��:�S���a2\"1�..L'�I��m�#�s,�K��OP#I�@%9��i4�o2ύ���,9�%�P�b2��a��r\n2�NC�(�r4��1C`(�:Eb�9A�i:�&㙔�y��F��Y��\r�\n� 8Z�S=\$A����`�=�܌���0�\n��dF�	��n:Zΰ)��Q���mw����O��mfpQ�΂��q��a�į�#q��w7S�X3���Q��/�ӗJ�6�����g2qs�_f��o��E�2�<��B�6�k�@���Z���Φ�#Ƥ�nE�c���Ђ���>`@\$cB3��:����x���߻�8��x�����J\0|6����3,��bׇ�x�4�8�1�����Dc�:C������ΎA&2��,�.(�N'N������78c���C�:��E��B�6%�Ш<�\r=\$6�-�\n:��ƀӫˌ3#��94�N)��\0#�t�4��0�3����W�aAc@�#�пU�2)0�X�Ikl*8\0003��0�[���T643�1��@�&\ru8�>#}2�m4�8C���{�C����4�� W���>W��s����2����Y�(�	/�˂\\����b �/��d\"'�~�,�r)s([��3\\��E\n�%��C���˥��z:P��A,�8�7�3B�7��B\rA Cx�3\r�JP��� *\r���7(��5B�h�U@O�wjI~����G�F�5��!�1�ܐ�ˎ[K�0������6� �gU%��Ҥ�:�.hAG��T�/�M�8ŌT_�q�o�c�{�2�!Ȫ:)+��`}�Xl�-˯m��b�J2EH�7��N���	S�8��l���㇤���\\[��Q�mGQ�}ޅ����k_&I؟��^X>em�2���HL��\rd𦩐���R<O8׹�\"���)��� ��VY\"\\e� A s�=\$�0�e��[hsq\r���De�\n�=+���ҫL1�,���~̈��`�q��B@Pzu��@\n\n\0)\$D�g \r(RA�߫wLf�i2d\\ʒ�O�&\$�����魇�����b~'Č9�t.�a�R\n�>#����o���s<@�C�4q�<:�@R�\\7����U���\$�*�g!)�I+%���3�֣��&��4�p�O�z&��.��Z�z����D!.�9�T�M&̨�CLC��'d1��6	�G2�u��r�1BxS\n��0���MST����8̖9�1u���%�K�V�4�5e�s�\$l1N���82d�#H���lMO�43M�x��CJ%������q2���'��@B�D!P\"��j E	��)&�M�a='�AT�\n��A�[kv�5���\$�8�7��9W\rQG;\0*{�'l�LGо�����5L�\0 ��c��dɅ�0�����C�M���b��8\n\n�\n��ˈtt�0�H���0a�3���b�6y�\0���H��+�����������lf�Ǔ�4�\$YɯI~'Z�,S�}�ą9�P�/S�T4Q�h� ���k��@)�P�훿����bmJ�=-��*�x��-Yq��ʞS���k�V�*r�ҩ�����`�cM�@>li�I���Aa M��/d\\��1	�P�v�kx��5C�\0^0��0��%M��z	}���S{�D�����qA~m%?D�A��%0���`Wsg���6�J��w;'c�^�&\r�8C&��~ˮTo�U�L��-#�Ģ�9D�[XAl�=A�gI���A\\2�,򂉼�P�IZѕ^FC�2;%��&C	��f;��C��Y�(C�画��^��01d����r#�I�&(�Ij�G��,�Q��n��Ns��Z�Z켔�v�K�^�Z�S�u�d�b�Y��6*�%�栓�kJ��r�/&VB�Y/=D����6�6/�Ի��1{�d��-}�fv�E<)u3Q��m; ���պ\nKuNa5��l�M]�\rn��7V��Ts�oᚳRli�`#��|S��t����-�J�D~Cɑ5�&G��͍��	劁��m���'���No���F�������#�J 4�R�}��?Q�}V��~=޺5ZC�2�v�m1R���M��*��F>f]LV���m�d\n�]-�bQ�����)*�z�V�����S�=*�w��^EeJjyc�M����΍���\\t����(�W#�W^��#��Lܿg�*��H�W����3��@>���\0S}� ��A_��,΂&�����k=��,�>r[���̉I��r�+��}x�����nJ��)(���������\"��wp�4.���m�l�Onc�\"��� +V�E�����AN��\r������b�0/d����%�N&t���\0���lf��/�\n�yO��#�����8%a�n���%\0�=��>/�7��S���J4��+���\$΂����0�#p�Ц�,��C_	�t;\"2.\"�	p�ä��Pz��q\n�t�-�n����R\$�\r�V���`��D6�b0l�\r �m\"�(l��I,�\0�\n���pe�\\.���JB®r�L�Պ���6/��\"fT3J��V�CN	��V�\r�,�/0v)�j��Ʉ�.��AF��f�J)财@Z��:(�*,�Dbp \"M�f��@�ξM�1�.q�����Ki�|5��Q���}q������6\$'m�Ɔ��ck���oX�ͫ�4\0��e��`� ��`@�4�X0�B��%�Kl@��\"vR%��VH�ld�V��fH�N*��rP���,��/��-t_�\0c��)�����f��Z?�MI�*L�(R�������";
			break;
		case "ar":
			$f = "�C�P���l*�\r�,&\n�A���(J.��0Se\\�\r��b�@�0�,\nQ,l)���µ���A��j_1�C�M��e��S�\ng@�Og���X�DM�)��0��cA��n8�e*y#au4�� �Ir*;rS�U�dJ	}���*z�U�@��X;ai1l(n������[�y�d�u'c(��oF����e3�Nb���p2N�S��ӳ:LZ�z�P�\\b�u�.�[�Q`u	!��Jy��&2��(gT��SњM�x�5g5�K�K�¦����0ʀ(�7\rm8�7(�9\r㒞���B�+�\\��c�Y�*�����+\"	���)\"�X��ؐ�eJT*���I�������P��F��t�\"et~�&�M# ��@�\0�7�M0�:#m����1�C��3���8C�Ҏ�Kz�˭L�9��H���4C(��C@�:�t��2t�A#8^2��x�9Σ���J`|6�-+�3A#kt4��px�!򞐖&�m�?2�X�n���j�P���<+!�u��11ڂ�H�����n�\"@P�0�CtƎ�\$^�#�����%|\"��e͖��\$�Y\\���ERR<���:����0������k�n�B,B��ة�Z��ժjlkR<�J�#X��Z��j�Y<ؽ�l�<HƖ�iKk�/#`ch�5���0�к@��d(@)�\"bԇ����Yn���1dF�V�f��6>�\r!��o�1lk�Ȧ��mE���ڼy���d�dy=s�dy���Z�>l;�s �Z�BE,��V��M����pWP���*�A0(�Ωm�Cό��v7LUH�����{�ٕǌ�L���v^��q���_P�1��h��1����C��3��ҍ�0�6J+�!k��&��%`*\r�EL7!\0�����3`\0�7��P�<���C8a=@��@��pu7`�9��\n�R\n9|%��^cK�l��*\$\0l� r}Ɉ���2xn�4�D���\n}O�A�U�TZ��\n@9)%(қ���J� }U9�Uj���R�!�J�'�獁U�3oVD�A�Gj��߂K8�\$T�L�jj7p�K�4蝐(x�>�@\\�S�2P\n	B(e����Q���\"�ԫ�un�\"���C��\r�P:D�|�`8eM���p��X��E^�\"��%'�!���	�F�p4T��H M���#K(Ô!�k�0�h֖_�n~������M鴖�l0�0@㬔\r,ןa�H� �P��R_\n�+1�}�urjC@pe�4���C\\��*ӝ�Γ�L�����!�Y:j�a�6�q%��l��VK��7�zSۣ�!GR38��UI)D:��T�D���fj]0@DoUn)9F�2����a���Π���F���T�S�9\r@�!�0���� �UA��[I�Jp��YA7d~O�lV(��V8��Q0�&k�{h2VץG�ZqBP�)G>G,AFr9J�G:��UŇJL�I&})B�\$FԤ�7��>���fA�H4�L c~�iQ.lkY\r?d�{��T!dE��gLc��h�X���|Gũ&�J�ڴ~CQ�^;��q���Z���`�1�|���rja@\$;c�6��F�Z�O�;I��Z��0T\n	�7. �\$��6n�#H��i�F�TV�rpB��Wŵ����p \n�@\"�p~�&\\.���s����!�v�'d�EQ���h���{�bؠ� �P�v]�������p�#��:��P��1Hl�/�q�A�-n�⪧H��%�d�X����2��j�օ~�2}�kK*6S���B+�][ѧf��Q.L̾��B���n�ț#��A�Bh�σ�0J�9ld��#��g\n\$r��̵Q��V�u��<JNt�L[d�2%�M@��L4��@d�\n`�L2��	Cq\"%\0*4ƹ��5��5��;�\"�-��;�l\nG\rvZ�lWk�-��\\�T@�tr��;��\\���\n\\�;�.�Q�5������8�Cz�Bhߖn���>3 ���E#��C�Ƚξm�̨�1��q�kvp��8d���`T\n�!��Ae�0iJ�^Ǉ4��(� (̈F�(�}�G�����-�x \"ˈ���'�׾D%'�X+Nx~�!O��Ty�b�ڋ0�RS&^W��^���\\zyfJ#��۲�Ҋ�M:�?Hs����\$�\0��ۻOq���wi�� ���l��v�/�n ;��Mag9>ܾ\\�O2\\�2� &<�KS�N�v�[̒�?9��h�x��2?��I\"��h�vG� y�b��0�1ͺȣ9B��\nC��Cc<<䡋u�X���=�}��V����.��������f,�D�	'�dM�\n�i����p��Šqo�?F�����p��q��OL�h.O��dD&ŀ]j�:�ƭZ2l*����B��(lL����ňW̜C0��g+zvp1ˤ������E�+\"V�px�\"�\\��\0Pń�&��/l��O�����F�͌��H��bB�� F��yH�\n���P��p㏧\n���M�kP��o��P�q���̤8�������b�aBO�V���k�1�)h�����P8����=\0%��Q&ݯ��V���l)�'12�� ���D���ʴ�C\nb>/�P�Ob/e�0ob�#�f^Ȑ�O�t��Cd�j?����Ԭ���d4�l�p�ά���b/�XDq�V��q��,F�,���<�L1�la�\"��^��m�m��;\r�G!��&0qo����q\"��\r,�*�!n7!��Y-��r�&q4����5!�\r!s0{�8:rHՑI2R9E�\"o�\"/�f��iØ �����f2/�\nEh���7\0r�c(�A2�+2�\$Ҍ����Ps�vf��\"2C\"c(,%r&ς�&���ΟҨC/���xߧo(�tއ&�G\rR'-��*�-��,/�p���WJ�G�Y\n���DC�@��*�K1�1җ��2D>���k&W(R��.��E��c�+*�V�������16Ҭk&q�mC���*�bE�l�`�M�\"rt.O�:.�DN�B�6`g�\r�V�@�`�tL�`�x}@�����M�f��\r��K\n��`�\n���ptHW;���N��go�	Fj#��0�F���F3�ؓ�;��ZN�_%u*��29��X\0EdtL{BƦ����_��&`�\r��T�L���I>�.�R��v�`-��!F/��c�3s���0+��ǃ��#0��{�{H��\0��-h�I�\n�:6cD4���(t\0��d��\$Y��Cq�8DL+2*XblsqIԅDK� &�Z��.e�\0B�N��ې{Ls\"�Q^b��F'���K����r�h �.^8�lf*�:Oq�~�l���|�F��ƹ\"悬��l�6��\r���V7s@qª]K�c�~x\"a\$��	\0�@�	�t\n`�";
			break;
		case "bg":
			$f = "�P�\r�E�@4�!Awh�Z(&��~\n��fa��N�`���D��4���\"�]4\r;Ae2��a�������.a���rp��@ד�|.W.X4��FP�����\$�hR�s���}@�Зp�Д�B�4�sE�΢7f�&E�,��i�X\nFC1��l7c��MEo)_G����_<�Gӭ}���,k놊qPX�}F�+9���7i��Z贚i�Q��_a���Z��*�n^���S��9���Y�V��~�]�X\\R�6���}�j�}	�l�4�v��=��3	�\0�@D|�¤���[�����^]#�s.�3d\0*��X�7��p@2�C��9.(��+z>P��K��Ɓ>�B��\"��v��i���>H���%(Ypܚ\$*�Z@�*p�����Bb�6�#tP�x�9�莎������1�c��3��0���0�c(@;�#��7���@8P�����`@O�@�2���D4���9�Ax^;�p�1̱@]��x�7��IR�xD��lWCL�4V6�H�7�x�.1ے��Г��8�S)���K�;+\"%�Ix�ږ�˳�{�pH��Kr���<��Y-��b���+�#��=��(Ȇ�KJ&��B��IF�4�!�Jxܥ��\$�K�V �#䃶�\\I�j�3	��5yB�����Hh(Jr�A%�Vr䖎�76�t�Z�a���%��hE0�FV�f�>QF�\"��4\$ҩ�f����(�?)A�x�/�R>ؿ7��hK����\\��\"��⛝\$�,��+�ye�> \nb����9+��2z\$�il����]��ȳ<���J\\���]:��/r���Fm�/(h��N�v�'s��v��}���N����4;��-zds�wi�z�<�~򵩒?���g��B ��������#�6P�pA��w��Sc�A�<Ef��8s9��˜�n�:	+�E���O��3=���\"�H�~&�\$�W�\$�%K�p�\0���pd���6PK4!E�\r%T��J�E]L���<J��#AD�����CNI�\"�����ێ:�+�I��r~��8����b�H��./5D%�΢)^���%%���⃣6�U�Et���@��|�FIŤd`�x���}1%��TI\"aV3*�Sb�@�:��ܟdL��K8C\"gS\0�M)�<��Tʠ;��Y*�xrV*����2�@�c,YN�VZ-/�G����с�I 솔��i\"�7�eN/#�(v�q�䛓ɭ!W�Ĉ���p\\�6d�)u2���T*�R�uR��R�V\n�Z@H�D�W�5��~��ё6d����8�=�W� ��p&mw�����97����g��\n g�mSTw^Q\\/Fܩт�K�T�P��d,uC`l�CQ(�he`�3@iFêwO!�:� ��:e��((���� jnX��Cd\n8G8��I4m�{�^�\r��b���=GB��Y��H\n�&���� (-����b��a1*(�\rc�`�2%=m\r�<9��T(g�J=:&��Czu�(��@���'�x{���Xۉ@��k��ЊlO��H'�AZ�u�\r��F��\"�Ó�4�0� gTMC]��e(e��匯�|J\r`��'t����#���hqE�II�}P��d�v�n�]ɚ�V(��h�)��G�ن<E�;_SZ��bnk*8��Eo��,`�}�\$Qx�D�l�|��Sjj����Y/����� �*�VRQʦ\r|F^��+�2�(�5� ���\n�%27Ă����JX�����)�,��Q�+*e��z���`���F���cg`\"?;��X�fw��GB옚�w�˩�U˓��Zɡ��!*Y;��#�/)t���2��g44F�nŞ���pY�[Cߺj�b�\nv4���R&�^���%ǌoRA^��of���ɍ�\\Bԏ����z����J��0T�{���l�����j��Kʦ�/�=knҢr���D�+?2@X.�Z��'-S���^B��*���	��I[i�������Ꭳ�=�,�7�\"s�(�rMຆ�H͚�s��3��K��V���9Tʝ@uG4�X�6Fɳ�,��am#��1��!X��d]�r�G�!�^�|�6F&�vZ\r\\ԹϷ�b+9i��3�(�q�pc△v�kx!tn�>�M�l*+��n\$B�0).�w:��J|��[��2�؆���Qq�HC�^���'\\��������g&_�5�3����KV�b���׷D�X��.X<�Ns�M�#��������8.w\\9�l�U��-(4!*@��@ !��֟TݸM��9'K{�r�(���'RnZ&a�^0`��\0�|s��>,��*���Lj��`R�\0�<Ȋp+���Pd����IB[�r+01Cdn�����<�p9��΋\n�� @�ǐNë\"�����o��l��������QД�) �p���6�D��0�`�H&��|\"ЇD���&&p�ߣP�i���65,���|.!</��dnd��f�m<-\"��?�*Z����\r\r.^�.��H� ��������\"H�0����P�J�#�b��?��ҧ�όJ�Br��@Bj&%�B��c��+f�𐜈XB�L�_����4|�������N,��0�e�jc��o/��mB�����!��|�%�h��R��|l�\"N:0���p�1䢍�|'!G�),txE�ffjG�j�(�zJb��~��}�T\n8\"�6%��-q��#'?#��A��v�!\$F\"fB�!#7!g�m���|�%����H�u-��1:#��+�,,�P�0P���\0Rڦ�I���)��M��.2�})�*1�*mp+�Vw�l�Ȓ�}n+�^�d���,�W+r�*Q��U2��\"�#=\"r��q/����,��?/�&PV����4,f�p@I��S93u-�1*b[��SZ�a+Pd+#jY��ˏ�쐎5*�T�-o5�)\0�Se&���XG�q�w-�d{��.�(1Pqi��\$x�+��<�\r�8j~��:�n�@l/�pH�<*d��|�,Βz���9��0q���.��ҏ�x�2���)8\"�8o_���l%����튞/K#�juC�ݓ�b����,��+�xĳ|�ў���u7*�	6��+�DS�'����E��{�S'�.��\",~��Dr�8�|����pp�648�T<�t��􈒲�tY9,�?͆��~7��@�ME�����Hf�ͦ](R�:arJ�;���7i6JB֊d%��<g�A�/6tJ��N�m3�_8��C��O��N�OR�J2ލ�&�d�+�ef�|�U\rT�^\n�O4����&��C���oK�Fu;P4�*�,���z���od*�D�2���/yEUI����t5Eh=T]C�4�9<�p��B�' �%6f�i�p�f�����O��UwG��DĈ�ÍK\$�4���Q�	j^3u�[�^�4�cY�v#�K�\$!GaQU@�b� ���i�ACp��Z/��8��\$�?	��11:��U�,/���(E��\n��	��\rM.�~h�HQ�kkG�92TMcVh_vm7n.ur+&:j@��\0�b�:bc��htj� +h��#_U�'\r,.\"���<�~��4�@\n���Z��4��pY1��	\rVl��L�0�CV�+��Ֆ��|��2��sţUg1�ru�A�/&^�R�%�jP6�N��4�2�`p�W6?u04�D�1�rGPZ�(F�������k��P|Kr��v��w/����b*TATRke���5j�T�S&v���tN ꑿ M-c�k*�r�7�zn�{��z���F�h��@w�;�i��6�I}��T��c㮤���L����|׽>�yC�[�G�l�.��|W�fA)�FA��v3�_;O��ͻB��J�\0���B�n@Z�������3\$�4���BH�\"צ�l��%����dhFD4	%�8v�t!/^Y�[	�\"���4�Ex6\\��[Fe�!�/玊��u�q[��0�>x�\r��E\0^�5Q0�0%N5TGĸ��&u,N8�";
			break;
		case "bn":
			$f = "�S)\nt]\0_� 	XD)L��@�4l5���BQp�� 9��\n��\0��,��h�SE�0�b�a%�. �H�\0��.b��2n��D�e*�D��M���,OJÐ��v����х\$:IK��g5U4�L�	Nd!u>�&������a\\�@'Jx��S���4�P�D�����z�.S��E<�OS���kb�O�af�hb�\0�B���r��)����Q��W��E�{K��PP~�9\\��l*�_W	��7��ɼ� 4N�Q�� 8�'cI��g2��O9��d0�<�CA��:#ܺ�%3��5�!n�nJ�mk����,q���@ᭋ�(n+L�9�x���k�I��2�L\0I��#Vܦ�#`�������B��4��:�� �,X���2����,(_)��7*�\n�p���p@2�C��9@��0������F+�z��3Ҟ�22���K�W5b�I�m���*yB�Q��8��|NK�2C��*�S��\n^SS�̐ ��l�6 ����x�>Ä�[�#���r`�5��\0�#��;�/�^=H�;�� X�(�9�0z\r��8a�^��H\\0յ|�7��x�7ㅥjC ^.A��7��`�7����7���^0�ӊP��}��+r�\"��ej}RPF�4�S4�|��0��/�_B��:�N�ss�%P,>��.ʞ� J�4�#]INU�@�B��9\r�B��v/N��N��7٦t���ˣS�F���T���P�@�S��RSEq�P:��y�5�\"ª��\"[��6V��6�.~�OzF0�J�,��j�A��y�O�1�0���j\0�H�4�L�+��Ժ��Q8��<�]�a��	�L�w�)q�}.k�%��DtU1���R1n���Í���\$�7I\"���R.���rF���D�F�)�ԛ�J���T�c�Dg!� �P)�#0g�!�#�݌fOV)HsAzaSA'vL��lR���,��K�b�e�s����t�`��L'�zn���G��J{5i����I���B��|��\\S1�P����<��&����q>Qz.P�~�r�a�8�G���8�F(�%�JS�zz#Y\r4�ʅ3؏��Gw\$�Y{�pJ	�(s��@rGt����0f\r��8��\0K2���(*���Cpy�4�U|�3��7�t�ָtR�0�p�kj�7S�\n�)��T�	D�_�;���X\"�\"RN�0*�|�r�k&�Y1b�q�4�E`�O�[ˁq.E̺R��9/�Ք`�z }@X2MaL26�w0^�3!ƀ�=�BC��#��r�^ּ���؇I�UbIJ\$@�Ւ~��kMj���J���l-���\n�\\��t�uֻCrl��y/H�#U_�\$6��W�t�`�1\n��������5�nd��45���\"���d%E�����Q ����d7A�W�\r���b<��9N��Cf�\n�W�9b�e��MK0�;H\rX��U����c�:q���nR�{!�%��\0�����ъR��Q�6����\n]\n/Q��eH����1�\r�T��@�-b='������U�r���+Uybxw����%s4�R7�j=P);\0�	�X��{+�����\r��c-f��w?��4U��� �Vx�> 겓�[h��\r�p@R��ٚ��K-�,��W�F_`Yxd(�\nl4WR��,(�ޖb0��)3��I�2��X�Y@�шל4��ID5x���B�;f�,��BL����k��!�V��H�y;��v�b@��	�>Kp8����2n\r���+���YVz�c��]BQ(\r!�7��T!��ϕR���	KHO�	��Rl+� i�1|��Qgb��=V�d�\n!�-�پf��U�\\J�RѾ9WU�z�X�K�\0���\0f����,P�-��j�������4wG�8g���x�lb�+LD��6d2��)6G5[!P���J��;)�H��#���dx��R�d`�6zm��ᣉ[���,)+�	D���I0�҈ɪ�ɳ�x�\r�@^I���a�Ê�������7��P�h]t��<�����)�\rͳh�%FF�h�ETx�c]8���#.�dc��K3�Zw�B苇\"<)������,������@N����b)��q~gP��ĵ�巷9ֿ;��2�����yr��>]�9��[�&a2)��e*��k��{�l�uOݜ�S\r!��]J�1�pS�����:�r�>���SO&��U=��;�x\"b	��۱� r/��`�W�<o�����D�HiB@�q�SZ��4d��f�8�7s!���`���N�=��i�e�(T�#��n�u.8�((�C��Ǌ��0@�*)�l�0J-��nN�G��n�Q�()O`�e:3�N(��~RNzO�<w\$���\"H �\n��`��M��V�,���W%v�kj}n���~��t݌\\鎀#f��A\n����~kF�@���8}\"c	F�֯�8Lb�I����O���\n�h��)�ꉶ�O1	h8�P��p�}p�(��0t'���� �\nn��fZd*S\0�|�#����*�)͞|���=ê,�O�B����\n�,���|��n��\0S	���\$�z�Κ��b(�gGo�e�	��Y�@M��\$'*�0��K��RR1�S��ނ�,,��f(��خ�<ol��Q�lj���TG�(�Q�i�b��OJm�.O��c\"��LF�zƅQ-ĭ��-�(E��!�L�\$�2lr-��5N�!�4�-tt�3�����p�P@�j��a��&0���7(�0��F��.�\"J�,�ꮂ�f�Q�x��U\n-�6�(w�#\nLk)Rv�����~d�9�0�R�(�pf�(��#5(��*��+���������,�%)҈nv�H�/��*��.����n�fl�N(��p\\��T	��#����#Q��D�\"����c�2j������#140U6��Smgu�Li/�H��/0ԯ2����1/��g�'5g�&b�ŠPy���L�d�(��Ȗ�����-�?-��8�i0�����PҺhs�-�8���s��g�	�k3�<ѕ����<�&��?�9>��cae#�(=/��!�:stw�i��@�p�CJ��&��AsΤ2ăuH=�sD+���D�a@,j�k^�2�/��J��B� %	,s�@���,�M,����hcZ��	4.TcK%>�����7(�?(l��*f��+X&����	.'�UAT!�^�ƃ��R�CN�j0��f�/!GIGNB��;x��YMBKb���؇�d�\r�Pb��\\��!O�k*���	!��S�ϣ5�B��Hdo�A�\n��P~N@��m�<����mⅎ=8/����E��HN�X�CH��R�>��@U�e3�0��/����)[R}?����U3Q�I�<�AB�L�U]c]T�Y2o[��^B�^�:��G��A�Y�ε�UY{A-��U�a1��a��#��i��8�O%a~�I\$'Z-�+c\"�cq!!j; u�ԵZc�!EpGZR����^�Ƥ{fJ�\r�YR�Y��9/Q�C`0m_U�@U.n��aO�_6)DD*&(�k䤒�_�\$���h�L�G��#�e�\\v'k������#O&�\0��\0R\0J�Z��H4O^Qx�i4�kg��hU�@W#mv�J7ilkqhq�p��e�qr��u�l���`�G_�T�D�s�'(2�IVq`�;u=kvsV�g.�Pe4�`cW`��@G���Uh�~��4��pgS��Q�l�aGD�E��a7oY�mz��5n�0W�C�w{pG{�#{��ƪ��5~*.��F�tgQ�BW�N�zt�R�h��'��z�GG�{o԰@��@�m\r �\rh�ME�qG?iN\r��\r ̕E�.���`���n�i�\n���Z��x>I��74;{7҆NL��B��K�|8Fï|�uG�-��N	J��qS���)N�̎�,dO츮�(Q\0���@7D�du�v�̇Q�3F�Wh�H!1��#j�v��b�2(���j�L7ga��	��j��[��@\0���XHR���f~W�\n2-�o��@�m>r!QY02r�b�8�V;S\r����j��F��eW�q�v	V��ÉRm�rx�ߔuf�[f���)��C�<#��@�^h�\nUϒN2튕&C��\\0�7y���>u�urp-�=2T%2��mn �S�9�T)M�dTε���)�f �����@�W����汷4urS�Y��6(�'{�+5�\$�s��:#`)�N/R\$��%=5	=h_�{O�g�g'[�Gu��p&�z@�܀�=C�tYoR.�v̑DEd��@�	\0t	��@�\n`";
			break;
		case "bs":
			$f = "D0�\r����e��L�S���?	E�34S6MƨA��t7��p�tp@u9���x�N0���V\"d7����dp���؈�L�A�H�a)̅.�RL��	�p7���L�X\nFC1��l7AG���n7���(U�l�����b��eēѴ�>4����)�y��FY��\n,�΢A�f �-�����e3�Nw�|��H�\r�]�ŧ��43�X�ݣw��A!�D��6e�o7�Y>9���q�\$���iM�pV�tb�q\$�٤�\n%���LIT�k���)�乷/��6���f9>��(c[Z4��P������ *0��53�*-�R���� ��2�9(�{T�\$((�8+��#��j(�(���0�h@�4�L�w���`@ #C&3��:����x�3��Z���r�3��p^8J2��2��\r����˘ڏ�#x��|��K���CH�FC�p�b����9�X��]0�\r1+D7��8�Q�%L�%u�7��*�;B¸�C�\"2b:!-�a\rK��c�u�E\r��ڟ�cH���r�#�@�kkҿ7� �3#���֋��Qˠ#���ü�n��H�(��c�M3Z3���?��b�N���:�������D\"��쾪nDV5��.5�hv0�A�h�ӱ͍Ӣ∘e��<�����H�Q��5�=3t��hun��=0B#PU.�P�@Q�P@h�����,\"')�h*�c�ʝ')x¶9+�gP����#lb4Ѭ�\"6��N)���'�����(�< �t�Cip������r����;C��&@K�솮\"8�7���]pC2��h@�3���	�LC��m7)?\$:��\0�u UB�9��ߋ�*+����]o�@��ªR2�*6���:��t`�HAY?����\r.�a,3�ܺ��\ncL��;��֋�prN	Ȝ�術�>���K���\$\$�@	2\rde+\"��A�+`.�ԐAтtiI*��)��],�优dLɡ5?@�`XnN.@�98����>	,�ӭ����5n�46X���Gd�9<3�!is#��>��j��aG�5��\n`�er���#�~�Xl\r�����c\0a��<�f��,�Y%�\"�(a1��1�3���Iwr�@��Ĉ�r�#��/!��/��i\r\0�(���e9�_ă#r4K��*���8�h�)�5+�CVm�O�A�;â^L��;�u������8��'D~��\r��o\$�\rC��r��t�!'L�])\$�鰥��� �)� ��;C�d�D�\n�����ԗ!��O�&Pa� �0�C��F-����d���;	��rfMV��a\$���4Q��х!�`)�dÈu5H�3����9��'�1�ē%�t54Tɔ�A�O\naQS�*U�A ��B���)T���,��%Qp � 7bC�*��ɩ�IL�s��G�}��ԑ*C\nl�WgiHF\n�ݝV\\\\��L���\$nX\r �lL�̓�hv#f�~�\0��\0U\n �@�A� l��2f�	 �Z�@(L���[���)��W��IB��:V�B��Kh\nc�yj���N�dbD���ޥb�.����t�|����ų�5bg�n��!����B-͂\\�36�=қ���P��[��Uh�c��D��s��7��D��>'�����>��*�ȀE�N�I�����S)\"S�E'd�@ߍJmŷϑL�{Sj̱�Dl��7á0Ys�me���zP\n���P�0�e����*�)��<��&�ՃS�;�qw���1�i���ޣ�A���lPF�P��N	����7��<P�%(��F�RRm��#�()-��x�A�a�r���Kզ�AD8��P�BHG�7kN�r8�Y��ABU	9�I����@�̑�Y{D�m9<��T8Gd��C�8�Py:�m`7%g�'�}��`\0����I'��|P���(��Xz;M�F���sy�m���˄�fQgo��./E���	���\$p�=�of�&��ۇ|nM�Ƕ���\\�p^�8(I���m���x�%�����Ha������ZԊT��O*ѵ��BI��F)���sugP;#�Sd8\\E�C(b��0�QI�?\\T!j}�u ]-���N:*UFy�I�w=���A�>H�̄�|�I��4�%��=�!z�8����9w�'�Y[��W��},��a��=q��|�+��h�~�뮬��+z�����k{����^#�\"�aa�|V���~j�hk耠��'�Δp���XO�C�Z�鐬�x��0(�uL���Ά\n����'�%��������B�Y�^�F�/@�C��/�n�<�&��P��r�+�'N\0�%8����M����\r��ap,(�M\"���F�y�0D�/1�V�L���,Z�&p'���KA�(p�Aic�H�d�.L��b^�z�e��O�J3f\n>��\$�>��~�(вZb������0 \"�� /q\rhԓ ȕ�âF��9�\nkB�P|�\r\n���k�oo���P�p�\r�Bal�X#	%0��i~ͥU	�	\"`�h�-P`f8�#�l�X�p���|*\rS��\0�0%�i��'fa�N�\"	MTa����>J\"�,����c�N���H��H��`2�KQj�#���a���'�^�T1~�D?�~�e8�(���d��CT�I�@�@#�A�����	 ��Ã�����C����+!�j��T�?#�t\$#Q�7����i�%�Z6�_qm#`A�\\=�I�	\r�j����\nH1+�cJ�Fm���,�\r�)����O*��)��T�:(���f8e��E:F�BX�`�~{#�W1�+El��*��c��r���=#���6\r�V�rx�o�+\$v�#�\\�MbPGc\0�\0�\n���pq�����Nj��\r�2�T7��3N2��4����U6�F�^\$nL�T/'�\\�b�10��&b �8���1�6V���3+���n�H@\r��ä8D@�:�B@�	\"֡��d��um�i��`�ʐT�E��\\����2�i#&\r��b�v�*��;K>��*�L�G-@���0��\0T	>è+21*,`1�iP�3p����k���\n����>(����0\r��l#B��I�D� �����B��'��,��8^F\r<n�B��_��0����;D;Ib98�Zt13�@�.�Ԅ�G,�\n��MN��2P:�U�";
			break;
		case "ca":
			$f = "E9�j���e3�NC�P�\\33A�D�i��s9�LF�(��d5M�C	�@e6Ɠ���r����d�`g�I�hp��L�9��Q*�K��5L� ��S,�W-��\r��<�e4�&\"�P�b2��a��r\n1e��y��g4��&�Q:�h4�\rC�� �M���Xa����+�����\\>R��LK&��v������3��é�pt��0Y\$l�1\"P� ���d��\$�Ě`o9>U��^y�==��\n)�n�+Oo���M|���*��u���Nr9]f%3M�)��pȺ��h@2��:��H!���0��p��P�:��\n0�ȍ#Қ1h2���e���1KV��#s�:BțFI4�+c�ڢÔ|0�cX7��0���@;��CI!�p��;�� X������D4���9�Ax^;́r?%�r�3���^8J���2��\r��:��˪|�B!�^0���P2ȣL\"���&��&\r�:�M��2�����h� 5(�S�1\"hıl�KG�Np�����>��\0�<�\0M�aX�� 6�j�\n�H�A�Dg�#)\\�c��� o`�猣0��Gò�:��Z9H�`P��DP�> ����B^I��\r�8�7�#`�7�b|��2�7(�p�a���h&B+�G`��Kp<F�\r#�LVD0��3\n7Xp����&.�(C�uaTģ�R��L4�%<9�c+�!���D��cҀi����:7��B�Zס�&��� \"�ը����\\�����r\\<�@S<��T����⠂��:���!ƌ�#���Ɉ�J�P��6�\\��K� &\r�,!/6��2���F\r��Ѵ�*:7��7K8\n��U *\r��}	\"��3N\r��%-�C�<3�+ˌ�X�ż2��Sގ}��'�w~�R	\"b*0o�B��S�FӀ�-��ʌI��L	�2&dК�`wM�ܼ��Cp/@e�DAD��L;�)Ff����4�t8�&�ungE^�\"JQ��/D�&�CpI��OK��S(r\\K�\r1�TΚSZmM�4�@���M��RPY?�@�G����St�͙5!�A���:3r��4֨{I����TU:����.Uh�:� �O5�1���\n�!����Fs	z/i(��0a(Fn�4F�cY��M~sfL!�o\$p��8���\0P	A��AQ(��ezo���}��o���l*�m����ސ�Po�%�I���+��\rҡ�����&��<\$g�%�nZq�<���`h&��2,#s&*\n7�@7�C�R�jnx���+qF���r���R\r?`��%fK(t�s�Н\nx��y�\"�� ����ֻ�&!\$���I/\n=D�RK��(�H	*?(����}�o�f��c��6A@'�0�A�I�'�`�/�cI\0R�+���ʓ-��y[M:��Sei]M�����.*&������yIA���VnR5*KDb�I�n8��3S�L�A0A�.<?Db���/I}����\0U\n �@��m\0D�0\"�d*�#Y�=͵�R��(N����	�1��#P�B�):�d�)�j�݊���ѡS�k	�He�4D=A�1���#jx�ĬXHh�5�����ĺ�y�,����ݽ�Y�8�!��F#\\d�T�\0\$��+�P,�8:��h1)�<䢒.)��Z���jL3g��a,=V�k�2B�Y�⊂�uR��=.�Y:�j\0]��2�vO,5��|4�U�k�(w���h7S 0�kr-W��cC�+�FX#!hq��L���lj�\"0�P�:%�ɏb�@a�{�s���j#�\"��F�E�˝��M̆]Ј�)-���)�\$h|\"6�ܞJ[�*�#j�.���L�|�Ãn����8�����]Ac��@CV4�ck�ˮ���NĚ9 ����}�	n%����	5ʭ\$:���^�+���G��&�*�ϸ\n�s t��>UY�O���{\"�,w�����ۻ*�nկu��7��o�n��욍M���dOh���ܐ\"I�AXDȟ�Pղ[�W)R���6�Q+Nr�y���4�1I+.5���^I��b�sb�ne��\n�Sz�M���O�/?<mLhV��M�ߥ����.�B�;�N`a�H٣@�yo?G�u\\p��EU=؋�E����/�x[��:�y֨��Y��&f�FV0��Qa��V��ڔ�K��R�[�o0�����TK�\"�TH`F6V�qE2\nr�a\$����V�O��!��t����y�N��yh�%������Ej�ŜMCI�:�@R�|���kX7t7~�_�3hƆ#��-��W������/����?in��}*V�j#�����������p\n8�ebڠ�E���a\0#�\0m�\r�2�����k��e�ͤ&�o��0�\n��-P�0L͐R�`�6G~��2@�N.����C�>u,^4��)�&���7\n�=��d�D�f�k���\"�� 3DS�//���O���%㴅˾~0lr'\0��-��xl�o\$=\n,^mf�i�V���ː�&�	��p0�\0@U�����MaK�f�����ɏ����\$ڰ���,��4���S�.����.A��j`��X;M̣\nhMظ-�@*��2��\$�	�\0Q�H�p��ч\n1D̍`yɮ���i*25��+�9�/�1�jq�H�[��	0��h)k��N�,�?Q2�N�.R�k%Q�Q�1�����qM��1�\n��L���'�,AD.��1�!J\$�����5!�	(��zѰ�@�-)��.�I�HGŏ�������\$�Oq�D�/\r%�X��F��둁�P���\$m���(&��&/T ��e4\r�V�\$BIF�]O<�@Zar�l�jAD|���\n���Zw�I�&��M���(�\\8��HR�\0���fЄ&Z �޾BL���f� �C��?��=c�����1m������\$�§�/q�c��P&h6�F�2�;�1e�'�Bi��N��f�O��'�0�Z�cdV�����(h��N�P4��~���7��8����1S�ɣ| �5	�����&̌}�8�\"+�_�(��^�S�ʏ6&F:��@k��of8�f>�`N�c, �gJ!E��)X������S��dj/GK2� ���i3�p2\0003�Z\\��3�*�Vv%6���l�8�>n�:� �.�q\"�D.�j�[�Rdh	\0�@�	�t\n`�";
			break;
		case "cs":
			$f = "O8�'c!�~\n��fa�N2�\r�C2i6�Q��h90�'Hi��b7����i��i6ȍ���A;͆Y��@v2�\r&�y�Hs�JGQ�8%9��e:L�:e2���Zt�@\nFC1��l7AP��4T�ت�;j\nb�dWeH��a1M��̬���N���e���^/J��-{�J�p�lP���D��le2b��c��u:F���\r��bʻ�P��77��LDn�[?j1F��7�����I61T7r���{�F�E3i����Ǔ^0�b�b���p@c4{�53��T���9(���5���	(持B#Z�-�((\"�H��#�z9�¤0�����i��.��6�#t��C\"\$��ɻ.V�c�@5��f��!\0�2�A\0�\r�X��@2���D4���9�Ax^;�p��0\\���x�9���9�c�R2��ɨƎF#2R�i�x�!�V+2ۏ! P�7�4>:)c[^���x�6��sz�CmE3Mӭ�f�\rc�ռ(�p5Ѣ��9U�L��0�5�HK\\�U��<�����h��8�*Q P�7���� P��#BH�1�C-�71b��^��k%\"cp޿��S#�p�=�C=�3���P�@P�2�\"�;@H�����FM�Bb`Ȉ����C��d7�,(K��\\�q*��U��(���2���w���vŰN�sQӍ+{FR&yR�����âs�F�F�z4\0����\$-[�#lnň�ƿ��h�]�6�V�P�P��!C�9���X1�P@�*�C����ҳ\r�n�9�Ҡ�����:4���b��F�Mx��\$�>�ەQ�D9V�8�jq��p00��3�0̡FIX�2E0�#e�j��m�4#H�R9�a�C�[M'�^^�؄�V�<��s\r̓l�	���x���y~o�����k�z�����_���`a_\";|�㼓�^`�y̹\r=B�k�\\�?���������@� Mՠb4�l�c�e̢�%lTV�G�oȐu:K\\�\"���êOJ))p�T��R�]K�1�rT�Rl���@��>D�\\9�H�*�P�t�.rr��C�0���C�jO�p4���7\n)5spX���qFII4�Wܼ֒e (�&�䠔��VK	i.%���dFQ9&���<�a�:�v�	��5�b-��șL*	<��o��?����tpg���<X���\$���\"�RJ��{H�ՂG����]ESHv����o��h��Q4��!��q����P������V���R-|�l8��a|^��IF���N#��H\n\0�y�Q[=��(*\0�:��\$���H4�b0C=\\�h���^�єޘ��w�#���I�jt.�خ�,u�(�͆��c����>��9	�9�))&Ū�H.��q�O\0C\naH#HgvN�;oI�9����\$�!��˘*�8�`��8L�]+E���W9�N���M��7�gS���\\��h_��1錁�ThQRiB{ �&R���@5�`�5������q��� ��\n�<!@'�0��P��\\h��(��l\$ny�w��0i���ټjW\n����Ř�8 \nl���r�O�}@oDx�*�C|�*P�~L��\$���\"		XC\r!�u��k\\�Y)5d�<�b��*�4��֑ryo��=\n�dI���	�T��7D�US,\\���ʰBB�d��7����&\$�����^�H\$�dU�\"|�q�������V��l�5�W;JZم=���\$6�s�CJ:-�*�L����V���ui.ˁ�/� + 7TӇ�\"�к�h��*6���S[�o�9�'�*���C���*6�	u�D(ǌ�Bj_�XOt5�4�����Y-AN�_�E_;�e�,��V��MK�z�u�*A�Z��2���/�s�	���g����(C8�J\\�����;�������\n���fk�j.d�7�z��	�E��E	�L�oa��A%�.��g�T!\${I�6�9@�ߓ�NDK/��U?�^W��(`��b�f_\nV'��5���O4p�Bm\r��%ƕJO���5�1�]m��U�|&�����l��eڝ���f�����|��e�1z1�\nuN���[s	s�~�6a��r�����ٹE�=�uhR��z;f2��t	]�y�xW=������:׀��s'�S�ؒ0\$�i���?i�YH��`'yrE���Ud0*�דW1ij�#��������ᔔx�7�!Ɣ�h�(�ߚ��>�?(�\"��&p������J1)u��}�n�E\$����^o���xC���Ε�Y7����P�F0P'_�0���O��<��4�~����(�:�m^�N��cK\0���\n�:���J ��6�HLT�eI�7�\n���]��ظ��j����z*\nE4�H(J��w��xFUfڄ�'`�U@�]��eMb+)\\�i\\殔%e�G�p+/\\YE�`@\r��e`,)������K\0&���2�з��\rE���L�I��T#��P�7��\"Vn�\0=@��\n P<�\0��d������D�p�Op���\0VĜ�0��#J�g��D������� C�=n�����4��k�;���yк F�p@�C�1M�Z��`�p�l�y�x\r�0��8�\"3.Yi����]bb��AI»\$�U��X`�I�<d��I��2H����:�F*2�8ۣ̤L�Bi}#N+CbA1x�1\\ٍ�xEjd�<f�i\0��l��Q�Y�X�Qu�ܱf0�;\"�!�\rц�Ƙ8\rZ\n�����\0�/\0P����QA\"O�\$�Z\$��#���g�H��g\$�'�L�f���:=��FE�	b*���2�k�Ӆ�<��A-����%0��P�+0�&�+?�l�o��\r�^Ú�F���UL�l�{-���.ɇ��UP&��B�Z\$D��g�#-�1\0�\".�4���ͯ2΢�.-���'�M2�:r�?&R�7D5��\"�g%R&@��5\n�w5�;6-/r�5��\r	�5��q<Q��p\"n˰m�Э��������:nw9I]9��ӑ0��Ȁ�#S�4s����%`�#*�=�d��\\C�9�#9G�:�;ξm��:n�5f�1Q?7?N�?�����.���(\r�V;a�Ɔ�I�,@i�?�oNj1B��Q�	�9�h��l\n���Z\n��ऱ��S@I]F �n�tt#TqGEl��3@T�\$j\"�.�D@�F�l��URh�B�? Yb�E�#��/��{�]ņ8\r�\r(�N}KC�o,��F�j˴�k,\n<@�R��VA�PV��(+�O!{O�6���n�nC@-̣	\rQ@����4�O�e1({\"�jV�S#Ss�!Q5H�<����CT�5BF\0eE��E�*��D?\0a5BG��(rN`,��2O�\n�t����\"�1Y`����2\"UoܾE�#�'U���(�r�k�^&�Q�R�r:�4��zȦ?]��6S�?[��\"4�B�D1�p��<!��C�\\Q ";
			break;
		case "da":
			$f = "E9�Q��k5�NC�P�\\33AAD����eA�\"���o0�#cI�\\\n&�Mpci�� :IM���Js:0�#���s�B�S�\nNF��M�,��8�P�FY8�0��cA��n8����h(�r4��&�	�I7�S	�|l�I�FS%�o7l51�r������(�6�n7���13�/�)��@a:0��\n��]���t��e�����8��g:`�	���h���B\r�g�Л����)�0�3��h\n!��pQT�k7���WX�')�jR�(����Vñ�&o�Y̘�� Bc���b�Ȣ�sB��O��2\r�Z�2\r�(�<-掎\r�>1�p�����1?���4��@�:�#@8?����\0y\r	���CC.8a�^��H\\��(�γ��z�ƃ�l���\r�:0���\"���px�!�N+0�cj2?�P������5����d3H��H�;ϓ��Ҏ��|	��B�\"�P�0�Cr�3�hh�Tp����:��\"Xޏ��(*#�US\r�|J/�`7��ƞ�L0�2���64#��:�SaM7B2+<\r3+�0�*U�:R���;�@쳎k#4��m��`��U	�L\"�\n�jp64c:D	��6R�m�M�-PZ9�l�)�\"`Z5��D)>����P��M��8��hK\"	��\rc���(�a�\nE�-K��6ã�k=��^j�d,�SE�ET\"S���)����:��ZZ�� :��<&�r\r2�oD�.Tb\\��b#A\\8���v�؄����^�dk@bOb�^ϴ�x�3_��O6���*\r��<����DC5�A�X���`�3�+[���rl`2��S���\n\"��c�:\$���%m��:�m�&�Q�Ǯ\r)�� HC��#IT�	I㔣)d�7K!���R�<��DV�� <5�\\.�Zp��7_��C#,BN���[�2�Y�G�:} �#�|�EH�\$;�� �rPJOa������s�̏����>i���2D횪L&|ІrtiI�UɈ�A����C�y�����X����λ4�޸ Ef�N�f�Nz�uL�9���)h���֡Wfm���L9sp���T\\A�\"0���\"pN���@\$\0[�	>(@R���.�ğ�N�)d�4fˊIrSl��D2g�Y�/��*�p�K�@f��ԶM�F*\$e� ��E�|7`�Ѫ7Jl;����D\$-)\n&��İ���Ҡ3��F������k�ɂ)Jn(��J�a.3���b|�	�#���&:o_��[���?p�	8I\"!彗�7'��E�О��?�1�%���L���@���)��B�O\naQ��\"�=T�3n���Hm^��9RR�j�������Zc�\rĐ3�����i� ����<H!9s'\r9�0�Hg�\n2�P(Cj��ܳ��F[�RB2��'(\\s(�6!K�'��@B�D!P\"�\n�(L�����<O�PR����C�)��-�r�\ncjgP��&�46(�[g,�?a53�4��%EE���PHQ��\r\"j��mM蝌����mr�e���5�s�����=�5�T�S��\\4����}��6H\"0Q	�.O�V.�uo�@��S)��J���8p*(�qsCHzURA�:4�d�����W2�~K��Pm�ST`	+/��� E�_�Y%^�4a�R݅ګ\$����mZ��l�ʨ*��o�f#Ĥ�����	Ae�5�����!�Y��1��`�����ֲDhC����*��hY���d(�5ꀘP�p���7)�@ʲ�7�,��6b�1�Oa,���\r�	١	�-�,�c�P!Ȅ�\0�L\0��h=��B�B�E��#\n[V+�\n�A��yT��3H��_��X.���Vg�ɤ��'	p�\0��Xo�)x���B�I-N��l�\\l:��!GU�9OU\$C�������'ͱ%`�8O�ərsBᬊ�mо\nyc-�*��K����\0(\"��.��\r�dvܓ�����xph%F�p`�ո�@�������͸��ܹә������n�﵎Iwyn5��.�q/��\"`&�C,S6E����U(L^��=�>T���Oe��3QǓ����:*�^����� R�,E��|�N�hAm�#\$홡�f@q\$C96��v�yBxFO�����B����ϹJvM]�t�s�\$oM���B�G|eóo��I�Fv���&*��7�ۊ�s�J��}���?�=�윗���2�C+�8��0�\r�ucŞ,qj�K�Ur9xD��h���p������r98�\0��Cx�\n�=B�b���J����Ua�<�TXSd�h�� ٠17��80;����PO�����pNe��H�O<Q��\0L�����\n�\"N	�g��=\$�����<�J\r2ͫ|40M�\0��~`��F�����(���R�ȚX���/�0��|BP)�qo'pD�H\$e�z��ìD\"��R6О|L�gê���OET/d�fάUlj��.��c�{�Z��Z-,a�iТƐ�/�N��O`�x0\"]l�>0��O���Oڕ����3C\r	T���/�2�P�1b`\$�Pl,����K�F�qHDB�uQO��U�,e-���^�� ���<�qv�Qz�pd�\r�V\re�\rm�F��L�(P��E\r�L��)oʇ�K��\n��	���;��R�N��2���OO(B�f�ܷ����#��	�|'ʎI�7��;���|!@Zp1��mO��U1��->�+ڄ��N.����\ni��9��'�0�E���F�T2���.��Ta���2Z>k`�f�;�6��_'N�a���Rl�+'0�E)'��N��\"b2+��)&�H6�Pe2[jڦʁ��	��������%r�^�Ҩ���B:r\0�Cc�d2���\"س��\n�JǢ�F�zOqo&��{�<_B�ƴTҊ/�P@�-7�#�\n:ԞQ>?p�&�";
			break;
		case "de":
			$f = "S4����@s4��S��%��pQ �\n6L�Sp��o��'C)�@f2�\r�s)�0a����i��i6�M�dd�b�\$RCI���[0��cI�� ��S:�y7�a��t\$�t��C��f4����(�e���*,t\n%�M�b���e6[�@���r��d��Qfa�&7���n9�ԇCіg/���* )aRA`��m+G;�=DY��:�֎Q���K\n�c\n|j�']�C�������\\�<,�:�\r٨U;Iz�d���g#��7%�_,�a�a#�\\��\n�p�7\r�:�Cx�\$h��0�H ��\r��;.,(��3��(#��;�C����&\r�:�1J���� ��j�6#zZ@�x�:��f�ij7��b���\n;�C@��IÄcC#Z-�3��:�t���#Q���C8^���E.�xD���l�\r�4���J@�}1m���IS�:C�z:���:��b��;���K���ԥ%N�Bp�:ǌ�摏@P�ë�`懠b�!-�a��bt�U#��\r�hڎ�8 ��xZ\$�N���B��ѺC���)�{&˄�b�\$\0P��R���0�3�w��:�eV�J*�.��R��T�}\r�̙T����6T�e�z�7�Z�ރ�p��(��h�h�(߅b)-��1<7E#][W�NBs��u���L(c�۱�D 5��Zr5-X�	#l�s8OX�<j�J��LG�\$�Fl��18�]�X�����AI�ݸ�۞��Z�i��0��C\r-��m{���lTV��B�C�8�h��´�Sڴ��(�3�dv��#�6#l`�����9P�X�7��Y\r�h��`X\\r�:8A8.����� bj� �\"� )��=�=� �gr3�}�~��^#���*�7�����H�z��\"��C�\r�y�:�\0�<w���2��e5���GF��<&(p��\"�L�`LA�2&g�SZmM���@䝈�/��~����#�P���XG\n�QΈ���(��b%����Z�^T\n/ia:�b���D'P���ܡ��F\0000�X���hLi�3؛��pG0�'XdS�#��7'�\0�C�o[�y�D }�1�o1d&�ԬF�k�G�92ը�M�9`\$�\0�B�dX�M='T(��B�J^�d6�8�oK�+d�0�B��cQ\$�膖>_��P�T2�0���`	%`�N������\0�B�֘ g\rب�t�L�;9#C� %8�:��8KٱG��OC\"g����sI�GAAQ �0���h��3d��ˢ�C�o�%�ڮ��0Q,Y)�\0007��ځf,�I�D6(�X��m�U\rW��;�2W�C�� !�,'�d�N�h+��r�u�4֛Q��P���0 \n���\nF/))E~(�Ƞ@�<�0�QH��gG�M!5T�:2rM��\n'�\0�R����A�����\"_ÓM.�*?�AH!f+���\"M�uP�ޓ��V������EOMNd����Hh\$S��3���\0� -]�,���8�JS�50 =5�l]G(�\$��J�!}ô�~DO\";cW1P�Dv�2�\nQT�03�5��ܽ�(���FQl���G�*BE�Q4����K��\r��W\"�5dKo��F[�����x���04���@B�D!P\"�҄B`E*���´Վ�(�u�'�[ja<8K�	�=&L'��^5{sѭ��ZR?Ԓ�)�� wS�Ż#�X>GB50;�S�Y�K�M+�gQ�<˄Æ��r���W���k���D1@�}��t�BL�K]&X��M�lMY� ���B~\n#8�T�l�p�E�A����o���%�TR��q�f1���fgTgH��Q��V[ڡ\r9�X�,��@U�/0�\0���P���y`<\ndpA;�LF3&��.;@��ë���mє�)��a\rf6�P�WU�`����%u�g#2��,���r�e��L*�\0q��ݜ:8��#]�+Ȕ���u�S��3YF���ybj�\\,R��ќj|S�) �KLj����ung*B�t��B`A�g(2<��q�]�L�1A.��O\0@�֒�k���ęO6��/���a';\\ρ��\0[ԏ<��w�s�yʝb��/�,�7�K�3�0�_ֻ�\$�y�`�3CH_��\nYnt��+�ˍ��> �RLTr�&�����RL�j<B�����+ږS�ņ�W(���)�4��Xu�Fx��\\�x�Hi�C�s��}¦h>�+��������(6Y�.�;�7G%?og/�+r���o��[PƳf�|�`whr��1��_�)A]]}��.�Ī'[�\\B��1E���D�IH0��^�t+f�[�lG�X�N�֦*��Y�8( P	�T,B��gO�ɬ~����PF5�>B��̲B0Fl���r�ϓ%\\�n���=`����n��nVz��o�j/�d�d0~?������P����\r	N�����#\r�6�w/���\r��%P�0l�0r�p�\r,�0���\0�V��\r~0\0�KO:���#�>6�p�Bf��Y�h #ZG�ZDC��,��J�zpb��r����� ��y�{�R ��>+'��-�M|v�BlF�hf�-��f�9Q4 Ͱ٣�1�D�IppU�����\nTp�P�q�̮�\"�������є�0�q����-�(j7C*7̮\ne�\\��#`	I�jlt�*�[\0��hۯ1��������g!p���M�\n	�1�e�^�\\�`����AR:@R?��\$RH:�T1�.�6� �2 �\re��W���C����il�-��+{(�4ˏ�(�y\$ҒG�^�æ��P܍�)��C�y(�+nE'�(�&CR�,R#q�\"�B!`�\"�܊�=`�\r&�S(�X�����\r�,=/rFIOq/�&`�wfiֳ�>D�R�`�kL'p��n�e0\r�W1j֓`0���I�+�\$��m�,�1&� �\n���p4�ޑK�6�&p���|��p��0n@��ɓ��P' ���O�%T�+�2m�؃R�pO`;/*?nD���\$\"73J0�b�먲3U'bf����H#'bEk�B��-�:E�sI�:�X���̒��:�b� PS�d�H/���H���@�wAp���)Co�@��\0P��BM]Dɓ\0Q\$u)����~\ng��4=��M@x�2��\"���Hk�jl\$�x0�!N��`�5��Ţp�\nR'D&��22�5\$�.�	�4�S �B\0�Ÿ7e_5�NB4,!FT&�N��=fWC�\r��jg\"M2vO@BY@b84�\nB�  ";
			break;
		case "el":
			$f = "�J����=�Z� �&r͜�g�Y�{=;	E�30��\ng\$Y�H�9z�X���ň�U�J�fz2'g�akx��c7C�!�(�@��˥j�k9s����Vz�8�UYz�MI��!���U>�P��T-N'��DS�\n�ΤT�H}�k�-(K�TJ���ח4j0�b2��a��s ]`株��t���0���s�Oj��C;3TA]Һ���a�O�r������4�v�O���x�B�-wJ`�����#�k��4L�[_��\"�h�������-2_ɡUk]ô���u*���\"M�n�?O3���)�\\̮(R\nB�\\�\n�hg6ʣp�7kZ~A@�ٝ��L���&��.WB�����\"@I����1H�@&tg:0�Z�'�1����vg�ʃ���C�B��5��x�7(�9\r��Q��j�����A\"���╷��њO9¦sL�J錆M8l(]43�\$%Ί��O�azᗩ�F���,�⸓Yn�R��a,# �4��@2\r�(�K��<:���[#�Yu`�5x�:#�9��\0�4��@�:ׁ\0�e�c��2�\0yb���3��:����x�w��\rUVLAt�3��(��㝳m���\r�-�V�(�c�#x��|�5p�vg)����Q�z��\$P��X�/;���o�D���;:�d�4��e��\\�fSV��)B@N�꼇8RBg%�B��9\r�>��\0�<��(�j��eKN�v/!��N]<M�g��B�+�Z6-DF��2C��\n�,�!�Z�Q�5��b�=��V�A0���\$Qq�7�rB�Ŷo6'l⬔�|�����)I�uBg۾��E�l�m�9%�H����<�����P��%�C�=�x�7lB��f[�V5ԧ���р�(���=�<��@��K �kɗ�PQ�Eȋ�˝}��� ��܊�8H����\"SP�/��\09���}~�P��U�q�&/(�s�CQs� &���\0�jt-��3XJPa J1�2�>��������ϋ���9��سK� L��7Dh���\\I�ᬆ�Ã�wE�'�B��+>\"��	r��_�'ܧ���i� ��\$BJJT3��ܘ�V��BA�:\"@\$�	D�̄�{MQS�M���_|�T�m�8�FW�S��0ܘ;�H�J��\"E�Ԝ��Έ)�n��@�&�\$��[��̸c#�\$z�:FėV����&O�O([/xR��J*h�Y5R�����,��)�M˙v�ԾMr5L�1L�b� ����ː|�n��1���eJ	((x�@�Rڳa!�U���Uj�9-��HdU�|.ƹW:�]k�w�u���K�}�aC�\r_`�R�D�.�^!	�H�\n})�E��VO�DlI�:�ϩ�jc�.�D�{(�i�/J5F|K�!ݏ* ����\\ˡu.�ܼ�^��|���b�3`pvg���ǐ������R�)e>*@��2���qp������L*�*S���vDEqV1VE�B��`͹M���\nJ5%a�:	�)��!Cl�'�?\$��(j\nj��,9g����	������Cfk409�U|�0u��7�uYqV� Z+6\"��@�\r��0�ȲH\"�)%q�ٕb�#�䝾\$�5��ea�y�4dBF��-�������_�~��8P	@��\nIr�>Qp�\"��JK)�7n[�`�Õhc�4B����Hv���3����՜DV��^\\t�sءID���3��/�:�\$��דo�Vk9�u~.����8-E���rj��4������+�y�\r��%(�\$�C�FE|�0��44MІ\$����Q4v�z�N�3�)�9�Q���`��rur�T�@VL뭵�����L.c�r��@^�DJ�v�)�{�ݕSP+B�.��a?n���R�4c�&�LQ%�EOy��&fy�&7�����	���~Y�b�\\Va��:20 \n<)�Hh����=PoU[R`ڢ�}eZβ���P:�C��_Ƨ���u<���P��J3���D�J&�N�^w��M{�͒��L����\0�0�lEVKb���Sx�׿�/���{gX�6S��p\r�v���\"�Q{��u�se H����.\$N9�\$p%��\"�XgS�&\\�8.m���tgA�P	=1���A�)g�)��Y�뉈4�'�?7g��Tm���!]՘h-��I�W���|d����`k<��e�)��hJ!�f�P)�)�.��~.	�q?i���HЀ����`��b�Y���7�S|�N��A \\�c���~<�����|R����J�ɏ��[_��C1��)'`���\"h�����,(]>���`o�t,���)�9ǨoLN��\rǖh>��v,B��H�#��m2!axQ/��\0��8�x&D�I�JqKV;+\\poDf��{���J�G��>�Q���~����k`�E'�ydڄ�4\"�w�s���^�~�cj/n�h�E�;t��&D�7h|���30�<�Bd��t�g��T�(T�_	�z}��p�nO\"��I��v�h�G�c�Y���а|㢔h��O��f�\n@�\n�� �	\0@ �L@�VE�\\LvV˪W%v��INF�)/D4J�yF��@#&�9b�����9�0�B�B��J-���G卐5�\\��4�h|�������Eoz�b�,kO��������	���5q�+Ѵ���.q�9�@����\n5g��b��o*1�'���ì���\"	&~��&�ȔbZk�� �����a�Q�)�qnޒ\$��)�-a���������ﬀ�yE�~������Bn���X0����T�/�\"�Z�b%���r4.24�O\n�~R���h\$��G?B�'⒘�v=Ҷ���%���x�U!�'ȸ�@NCVF\r����0������h�:�OV\$��s�(�kO.�*M�/�00�|�3!-쬏�n��s3�K�(D2��,�\"�I�r���iPI.\r,�B���!\nw5pC5�Q(�Gf�Ak6�:i0DIi�9�v=�,�J\$>��j��~�Ӆ6S|�Ӝs���'���7��:�;e:1�hX?ӕ;�l��<#�&��iNI��ө�)����\n,&p���P�0��{.'�@h2�91�)V�-��p��\$�N�B�CT��<�;ς~��}��/�M��ɩD¢^���3�|���s6�is���iF�>؛��=N�%�n0Q\$�d7=g7�T~o����C�\0w��5�R�2���xe�؅�В*�_���\$��&���K�uK�L-�\$��!�~.#1�t��M���4P�q/M�Bc��t5�r��Pp����>�)��gt�I�w1h�w�-U0U;����DSi9������i��	ÈEؤ�	B�fq��KN�%�oH*�ó2@MCȨɸ�\$&���4q���<���y�x�5eE�z&ЗWU�B�eHR�-�TN3T�\n�������ITpROuL�5Qs�C�H�1��F�Ah8\$M�Q�fe�w\nu�Vg�O���[R�C�VDTCJ�_H��f�)Q��HU!KUc�&y��UhE�]I�P�����bPby��,�)\"M&tp5�R6q����b�AJԛ!��g1g��fYe�5e�yh�=@�ai���\$�6�ehZ�Ugbm\r҅\rB(���?��#3&�/�&-��+C�Q'�*��y+hy×d��&4�g�{GT�NW�Sg�S�[6��pU�i�l=R'�;���n��1(!�=�3@Ѐ�n|�k�t6�<�ct�M��@��WeW?l7N��S<p6�T<*��/1Y��u���L�3�g�\rd�@:p�H3�b�8���S�y�O�)Yw-q�j�~�+P'�V�k�+v��(��+5��6�O�Oĭ2	ywdv71.�չq��c6�9�]�w�<�m<��#%wR�z��\$Ɉ^yn�'䪍�J@���C/�o�N��j����r!j�����+�1�O�\$��I�	J�F�R�5�&�*�N\n�L5�3�4���O�/\$\"�8SqC��x�5��IO\n�ó	i\n\r�V`���֞��B\"\\�\$��mh��<�Ҏ����2�B�f�R�Ax&�H� �\n���p(�I��ܮlf�HI��q�M��w󍒓zE�|�1�ÄR�\"J.\r��|o�����7Î�6�.�D��zB�FCsJ|�.o0�c0�c����\r�i�R�wc��C���G�Yn�-��.	E��Ҥ'\n�iz%�c56�U�RG�v+*o�Be-N��yRF@�N��j�\"!t�0On.Y_���0�&�΍��V�>���t8m'U�H����Sޚ��?u6z#�����E�YCC��Jw�J+�\rw���G_�= �.ES�f��OD>�<=gof:R6v�5�v�]��J�\"x(3��4aPq���\$ژw�	6PYD�Ύ 5h�+R��G�73ob��1��\$ۇ3�\n�w�Hj�ڣ�	s@q%\r*DGoR[�Z�*ę',IN0�㗜lt�J.�S]����:15�@����`k0��3Z�S+��˝��bH/Rb�� J��1�cq��g׈}�\n";
			break;
		case "es":
			$f = "�_�NgF�@s2�Χ#x�%��pQ8� 2��y��b6D�lp�t0�����h4����QY(6�Xk��\nx�E̒)t�e�	Nd)�\n�r��b�蹖�2�\0���d3\rF�q��n4��U@Q��i3�L&ȭV�t2�����4&�̆�1��)L�(N\"-��DˌM�Q��v�U#v�Bg����S���x��#W�Ўu��@���R <�f�q�Ӹ�pr�q�߼�n�3t\"O��B�7��(������%�vI��� ���U7�{є�9`\r�Kp��K�D������>�+�ݽ�@��n �9@I��P����&\r���7�S��D,Č�j��?�{R��;X�F��1�(�Ԗ��x�\0�\0�4��C7�k��;�� X�:���D4���9�Ax^;́p�����3���^8IҀ�2��\r�rD���rr�8��^0�ɠ�4�m��=7�:�9�S�7��&:�c��,\nåM*N0L#߶���:��8�����+�+B���\$\0<�\0Ms]�<\"6�h���J8B#k����P�7le�����'���B �3[Cdj;.ue\$��l@�:ڐx܌�,[�XN#1�&g��jD�B�|漱\n�9�ۊ2��+�-R@�]?����11���Wx؛�i\n��vh��☢&S�(ݨ���OT�T3\"���N���8ՏX��R���2�h���p5���P��4Մ<\$��rP݈��p�h�-Q���\$j� �2��/p�*D���n����*�z<�3τ\"�ugnb0ʗ���\nb5!<ֈ�2�C,&a����ݳL�R�\r�0��^i���B�R�_��7��[�xQ1�z3�`��#��	�#�0�.�A_��u�2��R�\nh�>�0�=3��8@a�*��\n���O�P��\0�4^c����U*�����`LI�;�g�}�rkM���^���>���(%�ӵ8I�L>�I��&G%�����l3��=%ZLԑ~/�84��L�p\r&02䨕�BX�u/�ƙS;с)78���bv`��B�ä�<�,�`�P '!�=�P�}���8��q#\$����F�\$>5\n�ӳ^De����W�F4Ĳ7���Vr�!��g}\r��x��#��b��\n�8P����fy�818꽲*xW�V?���8(�F��H\n7�3\\F�AJ% �c��ho���/9\0���&-d�+�pF�D-?d�����r��\rZ\\<��.�Q�j(�2.ZN�q�>�d�F���GI�\$���M���]r`K�pK�\0�Oh6���\nv�0��1����|�`l]	\0��S~���#�l�H��6�hxG��U�(i5��n���;]#Q�9\"㌾!�P?�������I�I\"a�͟�e4��9��h�Ɩ@�	\r�����^����&��hxS\n��!�rG�91l����^�z0䬽ɒtO�-X�p�R�c\$��(� �J&���X��:@�oŗ�B�A*K:�H��'\$h�3U��`B̜&�'%���*�� ��� U��\$-�bl�B	�H)\\b�C�j6�b�{�ta`P%\$/�>q���('��q��2� ��TJa2��ĵ\0���M�*�7l�R)��u���aR�H�)�z1�<q\r��j7�f4�t��:	��U �C'*\n�A� rJ�V>����u��b�R�)1az?��Y���B�1�8G�7��|��iT07۩LB\na�=1�>������ߌn�i�>�yE��Bvø\nPꎅ6�0�k\r��ݠ��A���.�p�b�s��1�Ƣkg+O���!8!DzW��%R�F���������L��FO�u�b�yN���l p�ϰ�f%Sh������	\0����R��6�lS�QI`e\rN=m���IAW@�����e�z�W[]Y/���1��{4�%=��6�-Ӯ����v���;3Ͷ�F��[��R�F��d�+z�l�U`=U�+�����V��cqnGÀweG��:�/�8��{ԍN�&l!M�}���'5�<Uk����%�jIE��E<��,��<��6��u�Qn1%@�&�i����.^Vo�Lf�iؑ��_�q���vu��\n�?b?�\$+�[�#�-�媃��y�VD�����[�=T�\n�w��C��>آe��0Y�}\r�1x5O�o�Z�0�B-t.��Jʥ��������rr�<`p��;�-��o�3V%>�� ?v�����54��x�\neEeJ�fIj9�}�l�uR�(xlV���c0#<�>@�y�\$�)�*���O������7��yVpa��L�\r�g�.(��y���B�e2ݭ�����N,��&��F�m�#�\\��jX��X�0p,cmJ������V�l|&��.>������-��/C�aH(���/C���d�/e�\r�(i\n*�b�k,/O�0�D;����X��u��8\n�ې\nK	��̅P�.��'�|���K�����iƊT�D7m�L�X�Z�L8�����l��;�F�o8u�,����mS.����/��g A\$!�0ZBм�B-%�|%��\$�Bk��ee>�J\r�V��.���NE�0�k�q�&�*hG�^/�op���8l��t�1�іa�8�.�]Rj �-��i�����4��Ҥ�/�%--N�U��Ae�q���7e�i��mhA���PE ��q��/����.L�Z������vW�?��Ce�.6��L��\r#���!#%t2C�%�\\QW%BVi�P�͵&h�-�&�(Y�&\r�V�����+B��i�0��xbh�@\r��F�\ny��\n���p�Æ0b�&��\0���,o��ߣ5\"8#����������/�����̜��K\r��8��G��/-2j-N�D�B���D�2�r~D2O/BHK�L��j�\nDD.qE�(/Q-��QJ��<9����R#G� �).\"��0�J>È��f�/&/�J��=6�2��d�\"�7�C5�J��\$���E̪�����TBPr��hK�����?�vb��b�&��b�0\$P�G�j�KΥ�>�\0��@�X������{��ɂJ!�7e^ңv�I<��J��1��<����XB(t1\0޽��-�1B�5�j>�XO2v:��� 	\0�@�	�t\n`�";
			break;
		case "et":
			$f = "K0���a�� 5�M�C)�~\n��fa�F0�M��\ry9�&!��\n2�IIن��cf�p(�a5��3#t����ΧS��%9�����p���N�S\$�X\nFC1��l7AGH��\n7��&xT��\n*LP�|� ���j��\n)�NfS����9��f\\U}:���Rɼ� 4Nғq�Uj;F��| ��:�/�II�����R��7�����a�ýa�����t��p���Aߚ�'#<�{�Л��]���a��	��U7�sp��r9Zf�Y��b�΍��~���=����(L3|7\$�8�0�( ������B`޶\"�	Nx� ��A��P9 �ҳ��*ԥc�\\0�c�;A~ծH\nR;�CC-�H�;�# X���9�0z\r��8a�^���\\�:�x\\���x�7ㄍ\$C ^)��(P̴���4��px�!�j+\$m���P��M�\n��j������~�\$�,\\\n�H�+�+�߶(j9G�����B�~��CP�\n�d�\"�*�*@MtW��+<N���#Ã�7�����A�{�fP��(J\$�2��P�(�#��2C`�Y���.:�#�t�A%Z�L P�w,��M%Ic\0���z�ų)��:B��4�K��2�3�T4�cZ��4v�.#\\CcL� ����0������cx1a�6�ncx���r�J�q����4�,����%VB��QU(�~	�H���\r�Q�J�y��\$���J�μ��!��.O	?6��b�P�W6IJ���>A� KHA����X7]�\"k��#M�'�n`��w��Ը6㜄!���լ/Km�#��cŢ�+���������0̍���-%� ��.��;7:Ʊ��q/�9�z/�0���څo�\n��@��nv�yS\n:Z���\n�p�*rN�B���L��RX�>�HdQ&�����XKIq/&N�΢fM��'�����OL�?�h���} g�͆u�UZj\\Ǵ6<�Hٗ;�C&\$2�RnaOzE1)�����0.I�9�%4����[K��/�4ę RfpI�¦����Y��\n���@���\r�ӻ���Qpp=�䞬�.b`��%A��B�xu?����i�j@\r�x�ư��O�!��\"���LK�cД�G��i��'�4�C{�\r�̚�R�S��x#)�2�\0��_�����0i��z*��(��(�L�;�����\0PCO��1��sC��4F���ܝH-GQ�7�xr��k-)AH�|��T_i�U(�ԍ���5Ĥ���:Iܳ0�qKZUX�H�6dmxb&%L0��4!&K�~��\r	òx��4��p~�q=\\�x��u*��1�ZP�ڶR�z\r𚄒(L�)q@�i2\$%RP0�c�_�FE���8J`��MF���\0� -mjh�����ܚ[\n� �GF�\"��P���2\\Lzڬm���L��R��3�ja,n7�3�\0�ɟa�\\��`�-�:uk�0>�~EP\nbXd��ʂ^rp(}�<3�֦U	d�2Ĺ���\0U\n �@���@D�0\"�冱L�\$�%I7NZ��	a,Q�eLʣ�:'Lꒅ���\$��t\r�2\$WP��\0ʞCa~.��)��	�i�����I�.��~Y��G*�PB����v����]�b�[� ���t�-��H]��Et�b�-������J0�H0h��¤U1	\n���,{F�Kyq��|���g0���@b�c�w�3;OL��lA�e�\rB���[�I�'.}���C	ʉ1�!C�f�UTW��3Z6-l�gXL�R<ň8(u��%C�M�p89�=��z�I�\"�%B�E�:v��ɻ��y�xP�'9W���kR�\"�`���ST(B@߳PQ�A�@��օ@��@ �lҺ�hQxp��_6���!(�%����^�-W�^�^�M�FIa����\r��������ݟ�qT�D�_�I����1k�5��C�\n�����_���|HF�Q���o��u�:.>!܋�7HI�\$�׃8�St�'\nA4j\r52}`��fl#�Y4Z��Wߢ�4��~�xP���K��܎.��<k�K�����ß&�sA�Lɸ�j���B�w_9�|[=�`�k��\$�%�h>y8wb�n��R�\\t7�2!�l�u��|�a�����۽��*�h�8|��;�_��Ըv��n��A?g���ΌrH�:x?C�\"�Ahȩa4o<��^ɧ�4�H�,�[=���:5���v[��QߙG,5�/M�=��Cv*Q�N�Z�����ٷf@��D5��qq��|���O�����-�ψ�;EU-�{U��'M}����(t�D4�0�1.���i�.+m�W��j��jbN��b�N0�p\0K0����Ê/���)\0*θJ���	��J���.'D�3cޖhč���h``D�r��k���\r\$���è�f= č�7��ͭ��B���G#BqL4�i���%bu��O/��+.,��Z�h�V�k�\\��;eS)zS�>0�����\r*<S�<T��.�0�%0�\r����K���1��T\0�\$\"�2��߀�����*&�Q%���\rq�Ɔq9�~s�ʿ�H�4 @Pӭ>\n�F\\΅�j�C�	'�B�_�ZÀ8E��\$��iJ�QK�ߌ��Rpd]0&��+\$Q���Dg��]�,�,��1�&��)��-L�\n�1�梨��l��͌�b�0��AH��\\��0��V�V�OJ��1�ʬ�!l� e�%R%�P�\rЍA�9qY�,@��7��.�\$�Wq=�ߒX2Q����̔ P	_`����c�gK��\r�#R�	zWG�+�1��h��2�)�W �`�Yo~ۆ+bl�2�VdqM�{��FFe#�9&  @�h��`�&eNs\")k�\\���\n���Z�\r��\$����M��#�~�`���f\\��|F�鍢\"���`���@\0�r \"������9d}l0�3Z9-������%\$y6�;`���ݢ>;p���f\n�T��4�Ļ�ɂ7f>�oZ�/���VQ5l��,`�rl����.�:��!\0ތFߒ��s�<�\r��B@34%\"�L��\$�2�L �PSX^E�j)��������L���Ϯ�\"R�ˊOi.b9B�.P���q��\nO�ɀ�(f�	��\$Ŋ,��&E��.�O�� �6�a��;L�<Cx5�eAf���Ys�U��@@�-CXA�|1e�>�C&��_c�<`�	\0t	��@�\n`";
			break;
		case "fa":
			$f = "�B����6P텛aT�F6��(J.��0Se�SěaQ\n��\$6�Ma+X�!(A������t�^.�2�[\"S��-�\\�J���)Cfh��!(i�2o	D6��\n�sRXĨ\0Sm`ۘ��k6�Ѷ�m��kv�ᶹ6�	�C!Z�Q�dJɊ�X��+<NCiW�Q�Mb\"����*�5o#�d�v\\��%�ZA���#��g+���>m�c���[��P�vr��s��\r�ZU��s��/��H�r���%�)�NƓq�GXU�+)6\r��*��<�7\rcp�;��\0�9Cx��.����*Fɖ(�����%I��&�Т:_+�k	��q�Bk,`X�k2��B\"�6��8@2\r�(�@C�6:��##�!o`�1��:#�9��\0�4��@�:Ɂ\0�-�c�2�\0y*���3��:����x�?��w�pP���p_3�sL��J|6�R��3ACl�4��px�!�B�Sj��	,Z���;d�\$�jB��̻����^ϳM�<�\$�k�ᐌ	D��Έ�\"��9\rҒ8��%~U6��dBOӆ���\0�2k�\"V����_��k\r�κ?���}X+�ImtԵ:L���ZUq��q��{\$D#�Yc\r��::��55S\r�<,���#�(���0(���/�����o�%e�@�^m�a���\n��&0)RBYcvV�Nz�__C=*bWVW��wv�M�j����\"�'���B[�z�Ȇ��*Z�0�%w^Hɲ즊��i������񵅎��K��?�x�!_�TB �9�#d���T7r����s(�:Q2�:9�KIir:hR@�<�^\0�'esª 5\";�gh^\$�8H�>C�(�f�C���6I)D�&���&O�B�Y�V��Fq�\\�,�\nǐ(�SXn*2DF(V�A&��W�MJ��[{Ww>�P��wƥ�'���3�ho���Ŵ�ߋ�~�9r?�����9-�s��_�D�ǜQXI][�{ (*��@�:���<\r�6�7NC\">M��8�4���{O��;��(rP�\$�8�@�+)�ڧ�	[B����mޙ;*�����HWUFM�!7ZX[�66'd�h@E��1,�k#�\n[cz!����B�o\\��6q\"�D��|O�AC��2�QN���M����H�X�!�)le�W��䒪%h�굒�\nױ�n\r��XTc![h8�4HT	�*3��ԛ�^\r!�6\0ėC��A�2�P��\$3a�'%\0�f�l\r��M��\n]r��4�CtX!��8W��̡Q|H��5�ڙ�K:�e��BhU�U�o�(���\"3�>Ʉ�\0((`���k�u6(\\�����a�c��:���I�r��r�d��ܚD\r�2n 9ʨ�cւ�����5\r%j� ARR�sL�>��Y�YC�dLɡ5%��@ia�#�Ν��]��0�P�PS\nA|�֢_	�'Ԓ3�uF�����d����d\$�\r�����S&ee�Pp`[��B���c�ƒ&t�����<	\$h<�4*RFK��j�����uHI3 ��\"z>�(1�\$�>+�CL��v\r.�J#n���X¦KM���h�QEVJ�]���H�KW�0(I\\�(�ڥ�%���.�qfM��z����K�~��=g磌�)m(��CϘ#J?T�ynHx�ڙ/Q�Nn��?»��U��3��8�~ ('��@B�D!P\"��|(L���)Z���\n ��еL�d2�?\n�U��T�I��hǑ��uH���%-�bAg~�I�\$�3��n|D�i��J̤��+q����7�iE����G���T�mGD�v�t��кUV��ʾF��+�щ�1tt�z�<�p���\\J�:�o���R���1I!��k*#+��k\"���F/@�� �a�D.�[�mJQ�Vs���T�},|�.3�Uܬ�,����g�SD6���Kj�v�-��ؙQ`l4�g��\$��`c�����K7��Nɺ�-��cC�H�1Z�-��80/l��\\�|�L��ğ5>G{�~��K���� Aa \\�RT��#���S��8��LjZ���bF�_wNB���hcp�)��7�Y��ԲŇ)�.BÉ/3�%}b��^�Mu5[]S���C�\r�'��k�U2���vI�ʹY���;JM�1Q4<٪�\n�� I�bB�c�q%o�?����<�{�'���zL`�;z�n'��_l��=���W|;f|��G��u䢛������2��2~`��JK�\n��+\$�/������8��s��GZ���Ͽ�ٻ��ݚ�><���B��#a}�D��80gV\$���j P�PYm���\0������\r����i����h�C&�����[�!\0P�\"n,� ��g���F��ހ���,�Em8~�3�~��q���l�-4φ�;8X���\$��o�n���g\nϮ��ڕ�4�涳ϰ.C\n��(�L�0��,Te\"WO��Ř��\\¯�W0�#lJ����Kop�F���-rk�D��P��T2è^A�\nlA�N{�?'�\0�l�Ϣ�G�(�����d�\\?��l&�l�LƸ��0�1,[�΃%���	Ў��]�h��m�~���Em�䅙q��Q�m���;]P��P�M��e)��N�n0^����,D@�\"�C�p�B��c��L��p�P�Ь���-�O)�R���qui~�'ώo�Pl<�)�-\$:��,C��)�zm�`���P�����p��R�%�Q	��&CE#1'bw�\n��vNY#�)��(kqv7�B��k):7�q��o��\$<kM,�JDcBV<�bwn�,�\n���\rDx���1�&+:3���\"Jd�0���W-~5��poCN����2 �����r���-	���\r�V���\rie\rP��3���f����\n���p�������V3�5�\\�/��)*�T�J۱O\n�KE�j��f�%B��S%㺘�\\ �}!��C�M:7�\"^��\\����0�l@ELF���ɞ�\r��1b~����O0#&�Z���o��DB�V���m��d:'�ؑ??�	r#�	q�7�\nw�6�\r4	@ҌѪj��;@��7�h��T��0zS�w�lcVz�=T�\"�`,�dÈ�J�0G���F�ˬn�Pi�}�~h*�fؗ��ݓ�����q�;@�j�\n��3D%\0�-7GeT��A	�J��BF�c@���t��6M�x�o'�H��,�s�)et\\@";
			break;
		case "fi":
			$f = "O6N��x��a9L#�P�\\33`����d7�Ά���i��&H��\$:GNa��l4�e�p(�u:��&蔲`t:DH�b4o�A����B��b��v?K������d3\rF�q��t<�\rL5 *Xk:��+d��nd����j0�I�ZA��a\r';e�� �K�jI�Nw}�G��\r,�k2�h����@Ʃ(vå��a��p1I��݈*mM�qza��M�C^�m��v���;��c�㞄凃�����P�F����K�u�ҡ��t2£s�1��e����#Q�4���p��%ɂ���S�ɍɈқ%��0��,���{�4����:�BBX�'���9�-p�0\r�2��@�29���(c��\rLP�(��\n%0�@4��Ry	�Лn0��:h*R94lj���9�0z\r\n\0�9�Ax^;́rO�ap�9�z��c��;�c ^)��ֶ\r�Xx�!�j+%�;�%@���a��7c(��H�ܶ\rc�魴�R׶,@�:�k�/T`�(#[�:!�#^;�HK]%5�)�@�#��8A�X�-.�%p �V{�.����hV!��XѲ�4#=�Z�7�c8�0���vD#\r4�3�ZX�	��Ӿ\n�挨:5�W�p2��\$Jӧ���&Cu�2SVr��/���f\roK��b�����6� �BR�6�EL��0Ĉ��i�N�e�K	);\r�/!�Y�'��1\\=�b�X��}+6�ϡ�.P�%�H:~�O���0KZ[#Mr�׳y�)��e��ÿ��XmiJ@�0K��4�Je�'��g\\\n\r-�߄>�	�Ҁ�ނvԧN��#��z�1(�[â�3�0���l&�������2č#L�6�ʴzR)�j4�4U=Z�-�tW�(.DP�-II(�(�:�c�_��t��M����K�'��4�w���4h���Qca�cϡ�~������?�����h�p/όمP�F^Z-h����]��#a��䤥\r` Fi��Ģ��:\\KɁ1&D̚Rl�`䜓�v\r���u�����2�LZ��uV��ՊG���I5	/��R���~Q�;2�\0��w0HH� Z��*�lΗsRHI�u�%��(tL�5���\r��uN�<\0ܫJbP,L�)ؠ��s�ȍ�B@uI��K&-�*�x���vT\n�)EB6�L�<3��*C�[\n�'dl�G�wR4�7��x;*��(/�)Eךw��Ie����d�vV�,��'���z�eXr�i�~��o�0@@P�q�����8()���w3O����=K\0S(e-,�Dh\r(!�4>\0��ܝ|~Q\nGM=���`�مd8}ɜ�t�E��S�QWa�Y*|�B��fQ���(��Q��m�����(D��[�DQt}Q�(R������p 	����XB��vd�M�δR\nK'�l� Դ]C��?f�<��j�KBDS�9\$Oɜ .�6UJ�OTMTr|<�����6liD9���`��iW���˒pN�8O\naP��)����D��\"�s@uЎ�b:�� n��3��D��	F�n�X�I�I����&2VOnJ�L*\0tO3�9���B%k�D���^JjsZ�4���pAb�\\a<'\0� A\n��^ЈB`E�hAK��yY�z:�Q̂\0�X��A[���A�:�堓gr� ��ĉ�d9�P�lG�\$&�ґ/%f̩3c�E�)�%E�U�Z\"�g���fuS�Hll���Ƙ�Ck%��D�#9�L�n�z%ޙ\"�+��y���d�KfN)�A,���I�^f��r����sX�0��&Ì���.n��ę��b�T`)I�Mc�ɱA\$71����GѲt2�3U�n��i���i�s��8�>rVb�6�T�m��uZw�Տ)�GlD��\\aJ�Nm	Zep�\0K������Z�@��C(s��3H�Z�RD�<풮PN�C>��m��hc��/\n�KY ү�ё\r9�r��+0��i���㻉��	{�{�}�%�{�曀�����پ����Р�͛�T'Wsp^�x��5ϻ��ili�C	p<�v�k?htE((�̾j\"VKn�2f����o�*p�|��s�;r�oQm��stw��#�̡\nH�rV+4�L�+t��Ӯ�#*e+�~(��^�\n�Loc�H�wА���B\rQ0xH9<P���c���0�|��K��2�N�b�t�\r/[7�Vֶ�D�T�\\�rhPu*>r�>�u�}���l�cL��p2N�v�u��K��x�R�q	� �Wk��\$B��k��� ��צ���Wlܣ�B_R�z=d��H�j�(Y�V�	�n9��'�}���]f��i�F���/˳�~�N�o��o�	cD��Rפ|\$�Ȯߧ��N-x�P.|p�-���#��N.��<��\\��B���}o�����>�g��O��ʦ�6� �6�E�4F��� \$vک��lp-�Y�L��v�|U��\$`��:'o�\$|��p йN%(J#�=E�\n�<BP`@���f8D\n�,�_���pxX�\n��Wo����TNF�/��A�UOZ��\" ���-�!OV�����r�)�\0X��+Ɏ�Q0Ft�,�g �`�b�hb&	��� 	D�&�����|#�%��P&�R��&��z��1o�p��:\0�]%֜BG�~\"�.��Ѡ�q��C�r�\$\"E�4���w\0���c�\rZq�^T��\0K)�P�Yp�\$��1J �a1�'lq���m�5�\$�p:<��h�\0/��M�rD@�>���A���v��ʯ��{��##���.Q���%��\$�_�`��g4q��;�&��\rn�p6f�t?ʺ�R�ŀU�`�*�`�@�cnL��8FtH����x�)2R����\n��	&��2����r]�;&M�H[!�/�P�� ��fQB����0�q\"�+0�)�'f�5�,�/cX5�\\����\"R61+���,W��w\"d[,�cmn�����P��B�-Cl�SX����m/cD�n�^f �\"�7�G/�7�p�∙Q4�L7�TepDDv��P/̆	����(ث�a��\"o����\"&Q�V(l���1�<\\F��\$L6��J�7,�ą�C��6�J-�<��<*�=LLf�(��>��8\0�%h%(�w�`@�q����Х�q�*�,NG��0�\\";
			break;
		case "fr":
			$f = "�E�1i��u9�fS���i7\n��\0�%���(�m8�g3I��e��I�cI��i��D��i6L��İ�22@�sY�2:JeS�\ntL�M&Ӄ��� �Ps��Le�C��f4����(�i���Ɠ<B�\n �LgSt�g�M�CL�7�j��?�7Y3���:N��xI�Na;OB��'��,f��&Bu��L�K������^�\rf�Έ����9�g!uz�c7�����'���z\\ή�����k��n��M<����3�0����3��P�퍏�*��X�7������P�<��P�BHcR�@P#�0�P���-c\\9��P��%(��̚����2�Ljk\r/Gڵ;-b����R ��j �E�T����B�����<���4X��Ѓ�)�Z���p��z42�0z\r\r��9�Ax^;�p�&��\\3���_5�sh�2��\r�rP��r�����^0��Ђ����`���;Q�Q��6'��:7K�1����\roT����Br�2��&62o��\n��7K��J2xƁ�M�l�m�:!��da����܃m������͕6t�����8�\"��2�2�22o�k	Yc-���+�#;8[�U����\">�W&�{L�J��a� P�9+�VT�c{�9�/���6�H���:��(��0��)�y���M	E`�9N5~eVD)�\"b�:��C�>O�_�)�z1LquRT�{����mf� P�z&\"9�޻��4n>��E<_��@P��.�aP3�h�Bǈ����q�t��8�E�n���\$��\"C�8@�[*�q��hJ]ث��p�biІ4&\\£�0�12��%4oZ�o���K���B;'|�kp3�F�YZ��3�Mz�§B#&�a���,c���0�.'ee�8�UN`MR�8-�P��#1!+}'�jZ\0K'\r���G���[��x��\0j�� ����\"���\r���Q3�*A�� ,�� ��U�\$?��&@��a\$)%u9�S�xOI�?(\0#�D��^���`��<�	�S�1ڇ����(�Րg��9l0��`�U�B�D��F�zϣ2�:�#Ĕ��}O�A���B�g�Ȱ�\"��r�^��E�|؁\0m{)����M�%&FT7(��يA4L5��v�J�)H�.;�i�d�`�1J��\r�ؔ����ƖØf3��7��2�Q�>F�p0��eJJ9(��;i*�K	ck\r5�:S�3���@\$8R�.����E\r1_1�̐�Cܗ;�a�7��P+�[���8D�IAA��2B�␑ڜ؃�\"tr�!*�֐��6��n�&�ܙ	�6�%�<�C\r6�)-�7��pS\nA�T\nRN�V��\0��\r\0%�3SFyxT2�E���%�P���� )�<��5d�S���3��`U#i�ҚsRj�Y^\r�\\ɐ�vȌ�P|EJ��hz�S�G%��#��ȍk�}.|��\0�¡>~ ����\0R;����O�R66ql��]d�'�9�4wYa;K}��\0&0��P4F��,sW>2�n�e��\"�VɃT	�`�=�3����6�ZK�e������t��	{7��g�XH`cV�2���p \n�@\"�o��&\\�W�~'�as>�!Y�#<5�Q\n�zN���C�<8)�Vp�}Y=�i�O[����e9�Dh��Hp�\$�\\�Ɉk���?������(��(���8lr21�d�?��*g�Ve�\$FИt�s�\n�7&���Fcl��3�;�r�� /�P�H�S4�>X�)HH:v�ܞ�Y&��%�v(ҏ��Q�vU_��A@�=�w�l脔���'���g�K-[�G0a��\n�J�2�e��)�{	a�\n�v��|�L�hΨ*N�<RC�\nmJ��\r���nD�bl��qD�\n�I��}=�E/��p��M�'i.^��Tq��83+�9\$�H���b�itГ���!�U��+�m��>�v�T\n�!��A��AJZD�)�GN�r2���{b_��\"�`�VL_8e[�a�w���-�dL���Td�MHc��=4����R��4�`��O;�+����E��Ә�������ۇawZc|p9�,�\$�o�Ժ�?5+�tP]���9�%+��>�J����\"�r9�cٚN�J����϶���e!2�2��|�0�Fr[���M굘�\$6��ck�����p@�(b������Ȼ�3-؂tζQ�:q�u����A!��'�Oُ\n��P���A:�Y�/��\n�u�\\*�gW}��O~�X��Ҹ��&�t����lN����+���?jL�2&O�����A\0E������2pxL%\0dR�O�_�N�+�efY���b�\"��ZC��`�g\r�ha���)TMPTS��_���|���3�N]DZ%iZg�§0*g#p֧�\0L��)�\0����\0\"x���mb�p�\$]Фll�U�қ�!0�	p�\n���\0�\$�Y���s�������n`�������\0\niԋ�p���� b��m��fb�!P�P��̅����(��/���Q�����\r�\ro��S\nB���8�'��kd��.e�RʮެL2�&������(P6�@.�e����n3�t!1d}�h��t�C�T�\rE������E�<�����\"q'1*�MJuB��\$Rof��.�2Ͳ�bQ�\0E{P\r�̷my(�r�r1]\0.Rf����P�Ϲ/�\n!R�c'\"�,��\r�6��:�G\$�!�F�Ͷ32K\"�NY�ݒ_#�<nD�D�P��w��\\�\"�º_�L!E��O�\0Q\nI�Y	�%�9)p�\$2���Ყ%%�\0002Xie2��e+��=�V^2g%��b0e,���n�,2^%�]f�2�C��ek���+�1\n�\$r�}m����s0D[0����3%C˦ݲ����0�ݒ�0�.\n��O`�R=&/�ᭀy ��-��4�1ҩ13ZC�`��_\$`�?f�-�+R��Y�4 ʌ����p�d�����9\"F|�%�3s�)�C�1径�!�X3���^�I7%�\$N�[����*0�\$S�,@�k�_c�5q�-\n���8iA\0�r'b���g�|jJ���3��@��Z���6�@�N���>0�CbUBtq�D\$�TD�����+^���h�dcE`��@��@nE\\iM�\0EG\0D��G8��1�w�!��|�p�vZG��Fq�T!���U;C<4;o)PvQ}9��;&��\"�!4�D�elu\0�L��N�;�D�\rM��:�5t�M�)L¢Th&;d�I�آ�)��:cF:�\\���O�\"�. ���KҶp�T��\"c͞��#�5͂y��#��ɋ�Ħh��\0\rB�t=/�����c��@��qNldǳ��\n�\r�p��YC\\��1�ޗ �e�	\\��BZ���=��>`A`�";
			break;
		case "gl":
			$f = "E9�j��g:����P�\\33AAD�y�@�T���l2�\r&����a9\r�1��h2�aB�Q<A'6�XkY�x��̒l�c\n�NF�I��d��1\0��B�M��	���h,�@\nFC1��l7AF#��\n7��4u�&e7B\rƃ�b7�f�S%6P\n\$��ף���]E�FS���'�M\"�c�r5z;d�jQ�0�·[���(��p�% �\n#���	ˇ)�A`�Y��'7T8N6�Bi�R��hGcK��z&�Q\n�rǓ;��T�*��u�Z�\n9M\nf�\$�)�MJ�ʽΠ��h���#����.J�ᎈ���+dǊ\nRs�jP@1���@�#\"��*�L���(�8\$��c�ph�0��º9#��4\r�l�G#��2�\0x���C@�:�t�㼼1S������x��rS��J�|6�.��3/)ʜ���x�?C*@1�p:��0��3�ޔ��X��!-��7��+pԷ@U/L���x�\"cx��C(��B�P��\r�]��\0�<��Mi[W�.7���ھ�B�ҍ��Xܓ�O#\"1�vT+H�z|P�� +.��5o(c4L�@\n���0�7P�f�Q�;63�0��:�c���0�߉�3\0Ǩw�\0��c(&ej����֪�n�U�\r4��p�_P�W\$�����d�X��(��T~�W�����M4��P�C	�0�s��#��U7�6�z6���:r��2��4X��5KE�)\"�fc��j��C-D��z���� P�!H5�C7�#��\n��R�>������\r+%Ci��0�H�1U�TX�D����ao�OE�N�Q�B��k�^߳�G���\$2�c�T:��2;ڈ�B;z���X֪����ɾ�0�I\n�S�(�wڠOz��ꇃ��~\"C�v���\$�hA���k�Q]�5\"_	�c�|F�#�ޚ\n�#����)_�\"�VR�k�#����@�C*SJ�],�����?)�4�^T�{���Bz��qɅI�Txr���[�3NJQ�eeQ����c�\r���(T���d���\$䠔��VK	i.%��,L��3��0`�N	ɰ�@��)Ĩ�R�X��Mg��JÂ<J�������(a�������1�\rƬR`�\r��	6D9RC�Uj�0�cJr�8a��\0��9ؒi���ު��a8���^�C��,x'��a���.@�\0��\r�F\0PU�L� eL��vG��\"���o� ���G��т<���;ç,��'�g/��|.vH-�)�v�\r�<�?�`f�~i��#I#��pH�!6��&	ph���3�elHC��_O����!BS\nA�PC@� ����֮��1+x�d���4g��l��Ʋ����Q='��������K)���e�\ra��ZNF�\np}��i6\$�a���_�aP��d��H��.�CP�R�#�QS'�0���bb�ɖ�Ț��u6�AS�\\V|��4���^�%� �.��U��PS�Ά#���e(䭗���a\r a'\$��S\n���:툓-֨tI	+2h��s�4_E�E��P��՝��\"I+	Ȩ)Q�� �0�BL	!h �)��p \n�@\"�n�A&�'� �yo=��&[�v���eYa,LM( j��F\"��u(d�8Y��Lץ�9Gu���L��Az.�z�C��^� E�h�ǳ�\$�+�E<��1�Vv\n;(�i�()�U,mt3�|�Тm&�S|���Uf*���#�,��|W����.��:p���PSce�`�-�c̒�����~��1f����1�b���^l�UԺ����bX�ȝ�.]C@2��\rallH��\n�x§��mM��[zqb�0�i�%����R�uR8&��<0�z@N��շE���s]���7-��S^��޾�F49�5�eOR�LvL!��ͮ��堀������!�f��J�*0*K�N/F��<�ތL�4ls���\"�_a��3��]��@���!�Ԗ�Njt\"�C���4��aI�Ć�.<L�qR���*�0�!y��L�,4�6Zkvw�+0�+��\\ε�|e���iV�>\"�S�r~�3Q'FC}!��Ӧ\$?l�ڢ�[��eP�ڼ~�!�\\��kz�r���2�����h!)����u^�\r;���'���4\"���(Q��w���C�]��m3�����TJc�r��cb�ZU]�S��#;�&�Dɰ�A�Sô]�g��M�4���������1&��7�oӢ�Kw<�xt�P�{Mȅ�C<hȰ���� U.0�d�V����A3�+�������G�c�o����XfJ| )�\$�\0uh�/�mƀQBh��Uol���	m00�\"n��@D���� �0�r2�B�P�`����nXЎ^ƎB�pE�	)��N8��2��T�>9#\n�\"B�B��E0gB]�F���q\"��.3p���\n@�v%���\\��i����j�\"�&���3��&��I�	�<��,������P�Be\"�\"±�05���%ذG��#��m	�>X�F1,�b�j�?�\r�L1�0��R���P���8\$��Й�k��O~SmI�j���܄+	7�Qʅ�O�U��g�\nf�%1�{� V%�N�c��@�de���R7>��+�4�ި�b{�h�I?Q�F���ʑ����M\\�gj�cq����%��������#f޲�NE��T�>�͘��C!͌=#�(٭�/2\$h��#R,���#� ���UE�%[P\r��2;	qi%�Rf3�`@�B\"`��F�\"��2�T�xWr\0j|\r�j(b�4.)#Z�\"�)ʨq��1�v��*\$�w��+oR{�^����Dxpw@A`�`Ơ`ƙ@ġj��\\��t1�N���+�.\"Ɯ��;	%4b�d�@��Z�\n\$i�1N�BD�z��.#\"6#�9F��ozeE-0�\\���E�/f�=�n�m�ڂ�/l-/-;4ˢ��C �8�����7��<gl�~3>(3P��L<�,(���n]�#	��P�o�i�c�C;��;��=:����	��)�s���y=�;��G@_r��>�.�FbPP��b�o� �B���ޚ��tB.1��>l1��A�;��!B�(����+:bd1�.Ĩ^H��V�=LH)E~\n�2�>@�/C�-j:#~\"�.lJ�E�B�\r�";
			break;
		case "he":
			$f = "�J5�\rt��U@ ��a��k���(�ff�P��������<=�R��\rt�]S�F�Rd�~�k�T-t�^q ��`�z�\0�2nI&�A�-yZV\r%��S��`(`1ƃQ��p9��'����K�&cu4���Q��� ��K*�u\r��u�I�Ќ4� MH㖩|���Bjs���=5��.��-���uF�}��D 3�~G=��`1:�F�9�k���)\\���N5�������%�(�n5���sp��r9�B�lwq-��m^�|_����|mzS�;Iʡn,���c��0N�(f�Lק�J�# �4���@2\r�(�;C�2:���#�\rp��0��:#�9��\0�4��@�:ā\0��c��2�\0y���3��:����x�+��%\n;�s�3��(��㜃!���\r�k\n���#x��|�@�kz������HJס�[���H2�	��Ѩl�#n�↠�jjT�B��9\r�R4��[�A��ZkE�t���(\nf����L�94��7\r���i�\"k��?S/�s-p}��'�U����2��A�;�dA㠿�)���.\$�'Hm��\"	���i-��-z�I���@��B��&Y�\\�5Ԫp�?T��шS�']!r�in3q��E��PuYMZ)6k�i������6��y���.Ի����L��l��B �9�#dj���7i:\\7�i�(�:LQL�9ω���4��y�U��p��Ȃ^�Ql��ս6���)���5�u6�C�����[��[�2 O��)O���L��'�����vs:�������}�\"��Y �sH5��2ko���\ra���'�m@��2^��\r��P�*9W]ug޲4�%I�t�)J����ˏ�0�pƠ:N�D&rLN������:p� (�� �u�<����3,�N��:\nI����jOJ)M*�p�R��K��0&\$�����M)���S�Iأ|���c^J���wLŘ�rDl濘`��3Q˄��rN�{�\n���W����i\r��\$j�qA�2������a�\"��b`l\r�\nD�r�5iP4��!��ѯ�4!5�@�B�(�\0P	@��\na{���(!�d*��nEa�7���C�J��*��F��:\r�%����Arh��:Kə+'�!��ކ�hsG�FX�/CppG�� \$ �C�h\r!�4&�|MF��1��㝄����\naC?���rD���t�hRH�w��ؚ�Եr�!��ݙ%o	�\")f�tBT<��~C\0Z�^#���Z�I�;�+��h��\\�&���O�1H����N�(�'�\$�8�P���2DAё����>\\������ُ<�z�R��ӿ�RBb%�I�/��E�<�Y�:Κu6�� \$�@��=\nH�9��-���sI��5Ǹ�*�{L�('\r�v,�es)u�B�&b`M=X&��(�djʖ��\\����H�x�l��H@g\r�-g����`n�Z����*�6�ؽ��5!�6�z�O�v�	V��W��!�Xn�Ii��� �%Gk�Ar���F��%n!�\n�Z�Z� �J����OI{�#d���s9Mҏ\$��ȣV*A�AbH�ޥT�[�b�J1l��ż�,����c�XՎs\rz�X��0U����,�ə�+!�c�iN� 5v�2Ү�YU1����n�C7�F\$��\\T�\"!P*��sg��\\�H<>19\"��ER��ň���c���m��z�s�0���j	�=%dᚐ�yI��8e��l㙏ٝ!���d쉃�P�\\R���:�!��^�i]5�Yϩ��ch�,��)/�7���#q@�-D�ViE�m�Uv19aCi5\rhۻ�UD��	7\0��[{�k=E�J�G�l�p�ݷؘ��@�WG�����Ć��� H4����Ēz`w^,�6ox�����gT���D�Q-rk\rj���B!�(9�3�Z��pD��׌�{*�f�EZp%�@p~\nr��9UƤr���K�PQz�)�gݞ�́��2��s[Q�m��Q\\����%�7�D��t�tI*��dBU<��r�pU9�as���L	8Â\\�Vm�~;+?}��vn�f�k쫗����	k��-���+�N.���3�{'WMbn����O{��8@�k�_�5�3����*to��S=�B�i�f�;�\0��.�B�����!`�X�ZM0��R,[������'^����߶-�N��?3I>�0?8�}{f���?Z0����H��_��Z:t\\�|#{F����MQ��j��v|݆_UR���?���L�����ԵK��.P�Pэ74�X%p�0 �\$��b�P<��6�V9�>�M��/��pD�����n�\0.���s��F�-lR#gԮ���'z%�J80xʐ|���Ȭ\0ߥ�9m :h�/&�k�9��^���k�-n<=�'e�5�Z�0�@�v6�������n���L����-(���H�d���~�n1clV�l4횄��h�.��b���G���q,䨆R��*YK����/\0�E1�r0*��@]\r]�-q&2Eh��,!�^nE�&3�l��:���1n�0�'l^\\�#�ȯ�h0ƾp����ϥ� -�[�ލ�݂<Xq��Ċ�ِWP~���gF�G`����4�ؽ1�몺���[���!�����O�o:��x���ǌY��2�E�-QIg#b ���b����aD��!(<#k�	C���2\0�BY�>@Ef?g\r��;���#b+�\$^�*>�n @";
			break;
		case "hu":
			$f = "B4�����e7���P�\\33\r�5	��d8NF0Q8�m�C|��e6kiL � 0��CT�\\\n Č'�LMBl4�fj�MRr2�X)\no9��D����:OF�\\�@\nFC1��l7AL5� �\n�L��Lt�n1�eJ��7)��F�)�\n!aOL5���x��L�sT��V�\r�*DAq2Q�Ǚ�d�u'c-L� 8�'cI�'���Χ!��!4Pd&�nM�J�6�A����p�<W>do6N����\n���\"a�}�c1�=]��\n*J�Un\\t�(;�1�(6B��5��x�73��7�J{z:H����(�X��CT���f	IC\r'|\"P�lBP���\"���=A\0�\r�(ڻ�AH�@�P�ݎb�0�c\n9�Ʉ|�8��Z;�,�O#���;�� X��Ф��D4���9�Ax^;�p��ǐl3��@^8KR��2��\r�cZ���`��\r#x��|����퍉()����5�Lk�'*����i ��/n���/��QUU��a�CRB��0\0�K\r�r���2h:6%��YTN5�P��S#�^V���ɲ�8�ž���c��m*i[X�-� �3#�R��:��P�ٿ���B0��cL<5�8Τ��+}.5[��CC�M��b�\r˝���)X��\r��5���Ch�7S&Ԡ�3�b�7Z���Cc����0��آ&K#����L���ʺK���<&�Cգ3[Sj��U(%j��➴����ˋ1{�BN%E�B�d�>�8�:и@�6ȴ������h�+��l�FѬNz��v�Y=����h\"(.#l���c>7sMj�s��<+#t�Gl[5�~ZP�\"\"(�\$�2d�b����(�-�8ʒ�-�����3QK5�x�3(Ro}��k�*\r�}ф�\$�c5��\r��U/ӣ�`3�+�A�YQ��q��@��\n�\"Y�E�EJ�P���I�:����wh�0��ș�BjM��8t�H�.N��7�H�2�P ����Ԃ�8�5�\$B�Q�\rO:%�*m�r3F����ю9�+!�,�4��L��\r% 2�����gM)�6���ߴ!�i�Ӻ�W	Ts��8hn!�>s�H��e�@�ф~e(��'�rJy��؄�ʁ\$8 I-F��R��9r*�d��\0J�@X'��`�JJ���DH��c�r&�%����\"s�/��%C4p\ri�2@�1�C�W��1��\0%R4eL1Ϙdɾ�VQ��t3���>�|�H \n (LX�2�ˑE2G�2>M��!R�&ǃblͩ�X���\$�L%\".D�a������W� � ��Wd�q~d��4����3J7-���b�w8!�4 ��A��x�i�?�\0��FY0��10J9�5qg�Yl\r��b�V�L�4>a�\"�I���>(D��G5JT�R��*G`J+J3��@t�	�I\"!�ӣ�vk��������J�,�@��(�jfs��\$��1����xS\n��@�X�dg\$9�P\\j�*� �C��V����':2ʳW\"�<�H�\rڙNi81Q�@�2�+�`\0�&�@X���ǲe]�� (\$�9\"�Z�i4��ɕ\\�\n�j�-��T-��ѻ�Ij��T�)�n*Z+�zB�PHf!�i\n���.��i��Ҝ��\r���\0����z/%\$P;��[����'j�X�؊\n\nq�bfS������N\0�)2Z��mMĶp��UpF;�Mv��1b̈́1)*\$�!���=W�b_�aC\$��MfM`/�L�����IE���F�\n��-��)�-(�\n\$����50\n�X��2�r:�Y�\\/�1��y��xk�YV��d��\0C�g���W-sM�кD�`�\r�\n#G�L�����U� ٛ�Q��=��GC��p���.��cD[[CK���<�����*@��@ ����=\n�+����g�4���m�]�c�D������\"��d�]ǹY���m�Ɠ!n�����p´�_�Nz͗r��Ap	��d���ż��*���o�þ�6�(����n�8.����y ��ⷸt�%/����s��7	��bq��\rQ*m��-��\r��ݬ|��S�9'�U b���:�����T:I2%0�9��D��\"/�l�O�4�������TY�L`=E�&��ڵ&�0�j�GFO��61m\\� �ޅU���C�xO���8�.�a1GN'\n��wx	Xl5���k��g��~Q^��Fcq�/j����A��ϫp�̂/+Y�U��Ű�[p^቉�'�B�3K�ڪa��*b�f�Q�a�r�C��\\�G7��`��R�������-�XG�����K���-+�^v�@IϺ�-��;妦k����R��'0D�����/ޱ���c�v����9+2jC��bV��5�8�oj&��2`�3��%�A�!Z�F�nL�nP�\0���UnJ<�l��-P]z��H�0j6Pn�� ��\0�p��S	�� ؘ�hHpM\n�%\"�z���+\ni&t��H��jd���3�Uaul[�\n\rd�&0�&t ��Z��/�%�\r#�!P�\r�8%�l^��A�A+�I6��H�+�\n�&M	�V&�&��~�O���̀��mBr�EM���l)0���hrW����|T�S�ZqqhF����{���'l)�#�O|���G�l;��q���`;��+@�N|�#��\"EBN2�>���3��+o&S�[�`F�e�)��KX`F`�gTU��\r�v\$o� �R\ro�,^k'\"�/1�E\\\n˲C�(�%�*��-:G��\n�S�>�ѩ�e'\${!E)&h�#&��|��=+�w�\"ֱ�4o&��7D:�rkҢ�d8�� ��+ �*mz*�g(�?�7��E�^�Q\0>�?�R5o�a�Q#�ƨr�P�\"���ƅ�q����2��r�0��3/R	r�@��%o�dX��1��YO:gNOS���3Ђ�-�3��	�AE:\r�V�oȰ�I��^�Z�\n����J�n�v\r��)H�~@�\n���ps�#7H^&�h~f�H���-��s�ps�/󢸓�'�R#�@\$BH\$J_��h^&/dv��\"�v�\0�EaB��� 0��+kH��:��\rM#�V�j	%�R�҈\"�(mn	�޶e\n)�x Nc�C�7�\0\\c�Ud>�S*�����N\\n�U�p��\r�J-�1ζ�N�v�6����Th+v�|\"�5For���%*8�(5cZ ����Gl�G\rF���b�<s�HtxcF�	���@��fn�Mfmf{%F5��A�h\nĐ�𾴞^�?�#��\"h�R�	CVU�uE���2-a�tp<t~+�-��cUQ��т*��\r�.�d+\"���\r��9�\$��wc:ۢ�l�� t\r��";
			break;
		case "id":
			$f = "A7\"Ʉ�i7�BQp�� 9�����A8N�i��g:���@��e9�'1p(�e9�NRiD��0���I�*70#d�@%9����L�@t�A�P)l�`1ƃQ��p9��3||+6bU�t0�͒Ҝ��f)�Nf������S+Դ�o:�\r��@n7�#I��l2������:c����>㘺M��p*���4Sq�����7hA�]��l�7���c'������'�D�\$��H�4�U7�z��o9jNzn�Q�9��<���)�L���d�BjV:�p�	@ڜ��P�2\r�BP���� ��l���#c�1��t���V��KF�C��V9��@��4C(��C@�:�t�㼌(p�ܔ�@���z29��^)���1�@��Aj���|���ҒĠP�5�H��9@������J�5l��<�˂��t�4�ɐ�\n��ޢ!(ȓENh�7��{�%#��K�+��\$�1�B��x�M#T��#�؍���:����4B2B3�pp��v��O�8��n�Z*΃�����\n\\�%o�r5'#:�2�h�&���l�rQ�6�>��P�.	(�(��P�9��ag�TK�6�	(�5��Z\\:�8>�a^�͢�(�3�r\$�oE	p�o���#wP�}U	��\"@P�]�B\"�ӱ�@@��h����l2�\rܽ��c]C��\0Ε\"V�Gv J�Nو�3TIrd�-42��Uf���Jd�\r�0̴�ih�uᏈ�7�0[t\$0�?�Օ�7��@�Ncʄ��I#�Cn2��R��Z�va�d���[�雪ބ|��F���Bq��q� �r,�\$�OL�(\r�xȲի,�w��3̓2�QXĽF���e�(������	�r��I�|r2Ѭo�G��!H�0�\$B̚9I򎁡h��9����f����6\0�Ԛ�Yl!������\" E~lX�{1J(��@���h1�y15#.�	9�%���6�hI�zD	Q���2�a1�)���:�A�[4`��_��-�������H\n������()\0��Ĳ�KBeIa���\$�̹��M��BniP�B\r�d;ƲZW	�*\0���BJ�\"e8��h��n:�9����d��t|M!َjō9���\nC\naH#E��I(K\r&P6��h��b��e�:�\"Jfzl����T�S�Z�5R�*-��OI�9|�H93CHk(𖄒LA;RqōviQ�qW�c�+]3��%1��57ɹ3PQl\0�T[�Ȣ�rhLOH 	i�n��JP����䞀�rʁ,A�3�A3`�H0�fM�<�JBR�y{���A� a*E3���r#h�r�r {�b�\$!�D*��X�*�	�8P�T�*��\0�B`E�@('C�P�T�Ⱥf��`���ݩ��H|�Xѡ��2�M\$�Mi�:�Y������K���9c.N�]sM\n퉲���Ӊ�`v\"B�e�0AX�����ղd���(��\\:\rE�R3զ�\r��x{�M5�^��[,���zW1�ո��f#\$k:'N� (aM�RfX2�v��M�MPj��.��HE�\"%�6���@�z����xP��>hD1���wJ2	��-�,\n���*���d�F�PR6�!B�[cP#N(\$���Aa N%�f�9�\r�a\r!���:�N%���Z�����(Dh�&�ʿ�h�/�x2؇X�_ʑn�УdN��(��bs)��H�G�e\0�����oB\$l�r\"x�F9�y�~H��3&��0��cr{G��R���������|�q�	i���~\0:G0�]	��C�\r-L���^�A~.��1���\"�����X���Ԏ\\�еIj�P��U���q�K�K�(jS�`N�����V�Ꞷrؚ�4��4Kj�d�(��*���Pajgx\"p��PP&EB\r-E����z-[Ef�=յ�)j���e�\0ӱ�FVS��y�kh��\0(5�p��������{l��l1pޤ��ɞ݈-z�|K���ֿ0NW_o}�X�~5����{�Zq���O�Xa�V?�w��ޞ'��;n�|��qݍ�����{�l�H۔�\0C7u	�{x\n	�N�܎��A�\0j�u�b��*z��&=b\r��צ���;ӫPt�m��Z:��s��t+6��d��6\"sX-�(�`���^m9�&�>;�y����Ɯ����I�|y���t\nG�������t�;u�0e�� ��\$U�з���6���Gc*0��W_�-\\����/�pA龨��ϥ�6/N������>�X-�cQ]�y����2vG�o�<O�	0e��^��{}-�^W�����\\�︅K�����Q� QoX\\��>�F�o������������;�\"��hD�T��Σ(i��ůփ/,Ќ��%�/�Ub�d�Ӑd|\rR�kBN�~d�\r�V���\"�ʤEjV�Dn���EBZ�P��ȅ'\n���Zr�К#��&�8�%�Bi�ް��	�����%�U\0C-8,ö>�2k� p�x�b��,7\"@`%�)��\r�BKdr��(c�:B�n��4����o�6r�v��ق�,��ʯ׎�e���1P�n�&&��1Un��SP\$ـ��A`��Qr8O@�@X잠�H���y��[e����J�'M�\", b^�E���&C��j��l/�\0V��\"/��\"b^@�0n�S��,�����\$�����N\$�%��\\5��m�\r���(4,�(f�d�� I j���j���O�";
			break;
		case "it":
			$f = "S4�Χ#x�%���(�a9@L&�)��o����l2�\r��p�\"u9��1qp(�a��b�㙦I!6�NsY�f7��Xj�\0��B��c���H 2�NgC,�Z0��cA��n8���S|\\o���&��N�&(܂ZM7�\r1��I�b2�M��s:�\$Ɠ9�ZY7�D�	�C#\"'j	�� ���!���4Nz��S����fʠ 1�����c0���x-T�E%�� �����\n\"�&V��3��Nw⩸�#;�pPC��S2�u�,�˳T�AE	���h2��k��� ���v��I���	�zԒ�s� P�2\r�[����F:!��C��1��p@�4�����V4212�����`4C(��C@�:�t�㼔0�,�8^���h���\r�C�7�Brݤ��^0��h��7���=E\r35�h�7�\n��\0����/K�`�*s��Mb�6\r����6���0��\r�r��\0�<��M9OT\n�7��\"�\n�L?S����\0004+X��C{�#��6C`��\nt�\n�/�3c�0��3Ǭm��l���cp�a�B|l�K�R��P��\n��s3�,��*5��YTe��#�X_C\"0)�\"`0�L+����\r��@Q�1ݯP��8����I��6ΰ�H��K��9�V.2�R�c���!NAf/�#T֤*0@�*`Ħ�Z&�2��j�o3��]�x�\"/��ۭ�Ut�N��#��z)������2H�B7��3���+	V\r��<��DFÌ��AT�c�p�o���eG\n���aJZ�%K�����{�7).�'czZ*2��W0�Mj7�'�0Q�B��]lt�Ǳ��!Ȳ<�%ɽ��9JR���L�t�ys�a3M4J��rl0���I�b�R6�*�l��ƴ�8\r1��G1�{ HR\$�\$IC����;�y/9�4��R�&�Y=@|QM[gF\ntГ��pP�\rH=J�ИHN;@���)0a�!.d|��b��j'o5��8]ɂu�QN���P�'n�8G���h5���nW)�-�E��bI		G��P�a�J(.��x�V��A\0P	A�[�\0((����Ȯ�	-j�2�4LhX)�\$yN��Vj�r!Q��;�����N�8���F�:�G�4է���i%�)|8 f��-5,P!U؇!ģ7\n��p��(C\naH#I@A%���\nA����`�L[\r+��*�	�!}d��.��^D�]J�� RHXy2\$�N���\rD�G�mx��]�\0B򌒆5Rp�I�J�y����FS��\r\$�(�&� rn\"�HF겛� �#ԆM�z7��t����\nm�̒�5R�Q:�1���Sf0 �R6/����C��w�p�{cѩ9�@�\$�ʌ+�'��@B�D!P\"�ڜ(L�P��h��R����9DӔ��b�]��n��aB\n�\r+������C������@PR&J@�T�\"̿N��Xy�FΛ̚����d�c���`�\0�nL�t��b!@���5�ȳc�\nF���h��j��	9(�_M	�\"��,��Z\\{�K�*8�kӺ�lҕt�RA⅝�)��������sTZ�/ḽ������)�*|�|xs��H����v��L@�I`�y��0��\\��-84���`����6+�L��2b���c�e��0���[��T Aa N�\"g���1�H��!�fx�a�,��x  �t��E�cUn��:�5R�!�o�<%��G�e�0��\0��@��C��o.�\\���b'p����vbhڱ_�`��@�G��/Ty�?h@S��t�?,�m\nI]_tA�!7͔�]�F�Df��#1jZ}BE�A����Z��~��ç�p������ŕ��!��Xot�d�{��y�\0�3�-�y/��\n�(+�aX��O�\r])�\n��r}���D���ܖwo�v�p�!,U�;,Y\r�\\�>��	v��M�zI�O����(�f���>V�T�C�k%vg�}Jw��`��w[��J�ey����oa���'N���k��IT��\n��i���a�ld�GSyt@HH�#����tsp�:/ME��Ao �P�PRĨ�u����y0-y���֐\n�\r1���b�a�9̪�B�Lf��{tI���7zo�P�o�藻� �==�xh?F��\\��\"��r4TΦi��H�}\"�aTt���s�F��D�D�<s\"�ȩ5���sv[)�����\$��v��w����}����)���C�lx/�>�y\n�iZ�0�f�G5�^g��>3b��q>���\"�+|<.���c�o-��C/�!�k��+��L@������#��v��&�Ď��������p��%�\"�O꙯�bG��p��Z� �D��O��f:Yp>��5h�ɆO��4\0�A�Y�/��\n�&B��px��.���fc���Ƨ�[�Nۯ�\n��K��P��\nЦ���	t]��ꎚ�6�c\n�e\"Z�G�F�#��͸ִ̰DJ�P��\"��Tg\"��-�\0})�~�#�W�\\�kn=	B��&W,�)C1<�&�k��̋��(Hc�\r�V����8��B�I[� U.��@�%	a �\n���ph�r/G`%��1�ħR̍-(b&H��(]�p��fL��\"N0�T�,�e�~;#�q����Z߬1nZ�i�*b0X\$���ޥ��f%��Þ>�/�&B�##��\$1�_H#\n4��,b�΂] ���(����� �\$3.�g�!�3\"\"\"r;�^�@5c(�\"�\"��vB��~o�Q�����Z��bD&�\r&���E��E�N6�\n�(���\$H�G\r\"�k+�JD@�j庢�dN\$-��+��\"��L8�cg!�T�\0ޯG����F-OЏr>[�\"] ��e2PD\$^\nq\$T���	\0�@�	�t\n`�";
			break;
		case "ja":
			$f = "�W'�\nc���/�ɘ2-޼O���ᙘ@�S��N4UƂP�ԑ�\\}%QGq�B\r[^G0e<	�&��0S�8�r�&����#A�PKY}t ��Q�\$��I�+ܪ�Õ8��B0��<���h5\r��S�R�9P�:�aKI �T\n\n>��Ygn4\n�T:Shi�1zR��xL&���g`�ɼ� 4N�Q�� 8�'cI��g2��My��d0�5�CA�tt0����S�~���9�����s��=��O�\\�������t\\��m��t�T��BЪOsW��:QP\n�p���p@2�C��99����E�8�i�\\��A\\t�/�>�B�� �Ёlr�j�H���8W���A�#	�ʨ��E��Y���p����\$�r?(�� ��h�7A\0�7�-h�:�|8Ar��1�m��)��\0�+8.H�9�����4�a7�c�2�\0y5���3��:����x�G���)�t3��(����9�xD���l�Jc46�#H�7�x�A�k��NE�\$Ўh�KJ	se���*�WX�E�t�)�M��1\\r���DDb��9\r�@��D���K�\$���E�8��w�vץ�J�I�.Q ��@>gI\\�S�t���J�\0S\$CEi�R�9hQ9��vs�}�^�7�2Fڌ��D�����:�K��6J��1*��d��NB0�6\r�ے�K��7B��&#��='&X�,E��3��Pt!	p�-V)Ic�7�З�\$=h��j?�&;�����y_'�AR��q�8N�7��AF�ģ���f�D��o��F�Ј��Xf�6��*!��:��u}H�<8Ct�W�na͚���{�f�Js,r8Un�ڨH*�A����?�w�㜷�6��H�MTT3�e)ZǒbRAD�P�7��`�<��l:̓0ͣ�`�ҘsO�u��R� � �t� �N(`��\$�PG�j�\n���T��g\rɥ(@��C�\r!�*��P��\"�Q\n)F(� ��2R�eM�wZr�@�*�F�U�\rB��z ��Y��O�F^��1�q��b�š�(�@��(�fa57�(@�C�yOh<\0Ҡ� .O�(U�TZ�Q��H�4����SJe�)�o� I\r����4\"@>u��K'E�o\$�a\rj�.���J����(�I1>'�JAλ]��@�86k@U��94�@�d�P~K.���r]	�8�����NG݆�X���<Ip�Cc�'�,P�Qx-G0� B\r�:a�If'�@\$�9�d��tOB\"\n�&s؏ʲV�༬%��,˙{>MCU�P1�Fn�y�6f�ۆUҘ�t7G-&&�ý&9��F�aZ(�3ԋ�5|��~��bkju2��q@nA;�>�C��a�N�Ρ�9Ûӌ0�T�L��5���!�0����F��|��9D3,�q�Ё��K��+���¤r��_Ag��'�\0��< ��qI+�a�-��45�O��RI�(�!	#�x��ԼH�4�,_\0d�t�.��>qM҂!�ܥ�̂�h ��RT���p���N���xS\n��!)�PI��&��bHd[sWU��.�\0��������c�T\n�h,mگ%��˺����i��?�\$��=V\rH&\0�I�Ma*��Hi�i�[�mC��A��R	a� �[x.�S��Z9E�&	�8P�T�+�\0�B`E�K��\"��#���xϏ���h�c�z�Q����B�%��b��+r>�2-F�m���@{��b,����Ep�9&t,5��j�^��3�ٟ<7f���T%��&!s�}�0�ĖQ��q-�0�^eEu�g�qZr��	�(���y��^��^�*%Q��+\"l�XX�����rd�zěy˨�g�g�9������\0���;�T����ʢ�k\r\n�&�������0Ay_V�kZL��Fȅ��MyM�ee�O�V#{��R�<��7-ix������E�ū�2o[h��k0����&�Ƚ�̘�7���HB��v�4\"�1&���@��@ �h#o��k\rr\\K�(�i`�H�g���*I��\0�u#7�:wF������鍁���6����� D�����H�_T.{��0Y���=iu��as �D�w�0�s(;�{QJ���4?E�g�ʵ<��dA�\$ ��qBN�-nșب��t��,��A\\2�+��}m�(� U��1��f�\"1�Ρ����d��Q��k �\"��� E��R��,^�YC�[��#����Z�� �ز�Z�qb�BG]��\"+Qk-�bY'vG���\$o��ib2�0����\0�/�>���r��m��\0n�r%�g���(ɏ�����L�\n`���^�VF\"���#����*p�M@��%?)^I�M\r���mP5B\"ݭ�[G�^fr��g���B�E\"0b�\\�����\nLּ��s��E-�&��mb�O�Ϋ�����-[�1\r�Ч0���\"��ق�*��o.O���P���찠ZP���Pn��p\$��\0�8����	�Kη1Sb>e�'��>�/>�E�]��\n�h�\"E�*0��@,����F�p����m\n��0b3��p���+����1�0���7�X��X�H؍o��m�g+,ʮ\"m�܎�G��XQ?�_��X�]�X���1@�2��\r11\n�F��f�a�Wb���t2����6?\"\0�0�|0�Vk��c��e��.K�7&�+M'�8b\$���ߍ��v��8�m�F�(�[rs/+D,��#��ѡ�{N�N qC�2��15\"r�-�\n�!P+../�#E�/���x� '���j��l��3B�M�#��1�e*�	ޛ��&҆���pG[A�a0=ap''�)!�ȳE��j&IH��Ŋ�ne�c\$RH/��q��n\$�c+��3i�7&`�y\$��m%�\0000�g�\r�V�\0�`�uDM`�x�	8\r�d��Nc�� \r��K����j\n���pu�R�HF9��gn3a#�g\$�� ����+��F9�\\��G*c�1����&֎6q�%B�\"2nڽ���4&�,B^���W#�D�EB��=�9Dm<'�9�/O�� �<h�<�b�m'�~Ɯ8R�@�H�6�v��Ip?Iƪ#�)/R�	J�+\"0'\"`�t�t5#V�%4u@����n�HtYΕ ��	:8ݴ>^o�'0U\r%qp��u.�лC4 ��@�L`���G\0aK��e�\rH2��(iZ�Z.��I�]ņ��F��n�#�h\"�J�g��t�ܠ��@�6C�m�>'�!��c*f�\0x��ҍU�!";
			break;
		case "ka":
			$f = "�A� 	n\0��%`	�j���ᙘ@s@��1��#�		�(�0��\0���T0��V�����4��]A�����C%�P�jX�P����\n9��=A�`�h�Js!O���­A�G�	�,�I#�� 	itA�g�\0P�b2��a��s@U\\)�]�'V@�h]�'�I��.%��ڳ��:Bă�� �UM@T��z�ƕ�duS�*w����y��yO��d�(��OƐNo�<�h�t�2>\\r��֥����;�7HP<�6�%�I��m�s�wi\\�:���\r�P���3ZH>���{�A��:���P\"9 jt�>���M�s��<�.ΚJ��l��*-:��%/�(��i�Z��d��b���M���R�#���3\n�jsZ�=1�hA�M܇�������\$�ˬ:N���[�pD�6D̓��j���*SS�.��# �4��(��\rI0)���(��'r�<�J�3Z�\$��Ԣ,�\0x0�@�2���D4���9�Ax^;ցp�AP�0\\7�C8^2��x�0�c��^�xD���\n�?)��^0��\n�=tj�����ǮT����/\r1R�?�-9D��d;*Űe��]��sy5ף��O��7Q�+v#�v�8����\"J˨�z�>_Ҕ�'1L@A�32�0�2���	;[,�*U�;��������J�]s,��C��F����q#dW<B�Ĉ�y�)m]A\0������/�OK���N�(\rċ+�(�y;o���BJ�y�=��j�6nH�.�+3�Ш�r��}�������D�)��\"ފrwh,��{�+����*�|)�\"e�'z��(��k��w�3z�����o*cN�s��qe��B�z�wSk-'v�'��ގ����NEü���)3%)m�-�\$dI�-�=�>|Ŝf�zb��?���;w��}T堿亼9�i,\"UK2�+����NP�1f.�@ӱ��t�ADaG�3�v��n~��7�P����+-@��w��i9K+�����I-fM��%2�I��eo����G�3�N0�:fzsX�H*�������Iɩ*Dr��Zq��T@&0ޥ�J�2K!`��>BN��y^bD\0�r̢�;�]O���Pr�we��N�ω c�)�t�J�PjC�H�cʪ�@2\n8E4Q�d�(1�?���ܡGTj�S��V�Uz�Vj�[ȵt����7���L�YK6'�h�Q�gM�Ij##��r��m5���N�O�l�̏F��&a9J�	4����b�geKn�|�J|Ap�G�'�RA��1u�����S*�T�r�VJ�;�eq#ڽW�2�����X+,9���Ȉ�u��b�YYvs`��S��V{9w�5�?c���6(�9F|u��^(�h� ��	4qf�g9W1ʫ��D����&TWM�q�9���ʪ[o����Hw2׼�k%ڛ:�\$��	����F>ɅR������ElM>���@��2�i��%� �A_&�2/y��j|ZnI�xV���bi&�Ш��Nu�'���kt%/�g�u֩�t�h��t�������k�Sd^��G6XM܄�E�y�@�NP{�i��ƴW�kK�%6e��L��RΎ߲�&##J�4��=Rĭr8�0����'9�9����\n������\"�bK��(;�-�3BIi,˩h�7͇�9��KE|�܋�1v�H(��0�i0��W��_t��r�����s�6#��\\zc�Z��\"c:4��(�*kCRud�V85��Hg8��Tsv��A�QT�2�5l���gU,����ZfC8�X�/���m�0��n�96@_٧�\r���w�Ii��#�fǾ*b0T\n��3�u/JMg��]�R	�^��*><0e�hsx��,�JOi  ��_(\r��g<��߳�n9��6�P�2N�)�^��h���nl��!�7�]�g/X�^m�Bl���oE�#l���k��;G���m��XK�#�u9d�3�OE��q���R^f��>��Y%��u��̳=r0�������tKm��RA�F0N���O�\0����7au�7����'�]A�'2#��W+�l1���6-!����9z�3�l�F�g(i.�a��[e���]�*�z�(w�v9����A��{r��G���{��H�y�2{�v�d�\$�5�'3Cq5�tDE�����#���l���{}�gKj-t�zз�_bJ\nTQܟ�<��];[=�`RP��?����{������\n.��+����z�؄Y�t�\n��u>��=J։�B�T!\$:8�{&^���A�Y���yG=8�s�����X�.#�\"y|��ͳ�jM�l�\nE�J�A������(#�\r�n{��J)��\\f�Y�å���%��d^�\r}��C���N0���^��F�w��o&�>'��ȎsM�ϪL@�**�ܮ�;N|���,�Ӱ&�nn�G�Ч\\���φ����\"���`/@GN�+7�2����@����L��C(����\\Ԭ�vВ�/1	k���H��x(K1�>�L\0���|�̦nJ2�I�u��4��i���Z���5��O-,m\r�s�Z�f���\r�ւ'�sz�p�xJ�\\9�\0�PZ����>��h�f昏���)/8u�t�n\n]�K�§��\",ާ��^��=�h;�	0�멖(E�mj������R{���N��Φk�O�oꀧΔc��\"�\n�ק10|�`x�30��_����\$����ͧ�d�O����/����oq�/�ѳ\0��,�����ޢ���r� ��w�^h���ε��fM�����n���J�<H��)RFEQ��Op��,�\$M�b����Qj4J�AJV+t�\0007��k��hm�'��N&)���Ҏ'���\"��hi���py)qBAR��d���r�����xp���l�'\r>}��7���Z��R��V\$�Z�Һi��*�|r��q*څ�	�OT�΂�zd��l���+&��0�N�}rJ)�� .|�sE%��}cN�O\"�H�hg���~�o56�p��4���O7k�7����\\�Q ��	7��'='M�9��:�Cvߤ:�� ���V{�f�h���u'�mD�P	�r��.��0�����W	m]��!��>2%8�%�!�ș�92@�\$��\"\$�:S72d(R�;��6��d@��A�<p�2#�B�~y'�<�?C�!	.�CQ�B����io/+Q���wn�*��@Q]9q�İ�AR/5��5Ө4�WH��;TF^n�3GJ�EBq�Fb��2dS�)ӱkVHC��52�M3����+aBQ�&2wL��M�EM1�I���QJ�!f{@��A�\\N��0�@DY�y�Q�78#���X�:4������І)�(b5=ϻ2ї9E�#&���|�G�C�F��욑|\\�Bh^\r�V��N?�A�<mSS0gRQ-��aX�J�g u��\n���`p�U\${/K(����q!1r��O,�OFȨo,\$r��{K�>t�ێ�P^�P��H�[աc��S��4oS�YK2y;�ff,�V�|L%BY�J-��kS ^a�k�R��O�0pp�ϐLv0�\$�@)�~���H���+&5=�|E�΄�J�~�@�P�Mref�,�쏪`�&	�,���H�L��U�f�o55)M��\\D�?X����L�g��)R�i�)L�MSU6��F�+j��d�r�f�k,�Sg�cK�]�_(�3�)G���'����NP�i�;��|�Z'm�(��n�:��(�{�gl5b���'=^�7Msq6p�D�Wu}2Q=h-�(�>�4ݵ{�he���fDL��u\0����\r* �qr�\0dTZ�k܂�DQQ��U�.\$�6j�f���4������";
			break;
		case "ko":
			$f = "�E��dH�ڕL@����؊Z��h�R�?	E�30�شD���c�:��!#�t+�B�u�Ӑd��<�LJ����N\$�H��iBvr�Z��2X�\\,S�\n�%�ɖ��\n�؞VA�*zc�*��D���0��cA��n8��k�#�-^O\"\$��S�6�u��\$-ah�\\%+S�L�Av���:G\n�^�в(&Mؗ��-V�*v���ֲ\$�O-F�+N�R�6u-��t�Q����}K�槔�'Rπ�����l�q#Ԩ�9�N���Ӥ#�d��`��'cI�ϟV�	�*[6���a�M P�7\rcp�;��\0�9Cx䠝m�vBZ�!�\"L�:��dB@0R��\r�M/d!���DA�L1p�t���4�5�����E�6N�ga0@�E�P'a8^%ɜ�\"��X�2\r���x�9�P�c��8BS8�1�s����\0�0#��1#���H�4\r����;�C X���9�0z\r��8a�^��\\0˒�'	�x�7�H�EѡxD��l%?��4\$6�#H�7�x�B��y��<BiN�H�E��I�B����j/E�h�*LI\0��cټŝ�Y](9Zu�EK�S���Ir[��P###�X6��y\$��E0�PBDqa�G�(�LN�π�J�#��3���:��%�g�D�Pv'+:���c�A�+�TT&8�J�eX���?N)+t�ec6OE�JL��>� H #c�`A=Cd�9�c���\"c�U%�s�jnc��X�4}\r�\$T=s].�v�E!�S�m�tû���=ok߸G{�3�+۰Al��ݰ�8������~dqt7��]l��|�w��B�|�Ȕ�P���e���H�=�ʜ�	A�w�?���䌣��U�u��\niQcO��\\X�ڏtaz�،�S�\"���V>90���JFJ̐�5nT�#,��6A�B����\r��PܚS�r�x��ϨsQ��9@@��	�h�\$��0e��D����d�e��-H�5FYk�@�؃ZdWA��3��A�<1� ���\nMJ�u2���T!�Q�P܃�r�UJ�0�`试`\"�u^�e����3b���V:��\$K�\"zrJ� \$(�Bt��DZ�Z�\\&�ןr�L	�\0ҥ .R\nIJ)e0���T\n�RD%N�J�UO9�Fd�Hm\rx6�����!�A�� �\rj�3�\0�ti����C�M�H	r�%R��&����PП�|_W�\0004�ƴ���P�V� ��:h�%W�����\rP*\r��t�eh rBW�D���D'H���k�H\n��PUI���v�0:�~+e|�B<Y�A�j�+�8��A��5��XlA�2;���t�At�4��.*��J����l���dN��D�6�T�\r��C(��\$Z�\r�1��C:��z���S�=��H0�F �f�t'�l4�G�j.��cZD)%-vv�ܚ4GLJ�2�R�aFDț�u�G���Ɛ�w�\n	H)v\\u��.)����X���:T*`���3���-��dR�Œ4���m�Y/��r~���D��xS\n�����`�O0�-b�׈{5k��C4��F��`�/��u2 ���w4PP\"�im=��V�W`@�)(oȦ`�/=s� ӊ�VKIy1�DK��r���E��Ĝ�@f��'x���&E���E���xϚ�q�ߞa*a��Ntu�v�G�p�E��S��`Ls,(�!���/��<�tF8�2aD�2%���|�NY��/�\n�u1�svhu�W��Q�n�\$v#�~��3�4�,�߬��d�CZ��3z���ջ����~Z8�\r�y�(Q��FA��J/���EBhX���I�����T%�􅿄��h9Z�v2׬������Pl��\")����,\0�d��l<��䅐��6��L���p�m����ע&���ؒy���bD �x�\\.��U�4�=�]��\"���-���Hvp7��bN?IE)�U流q���P�n�D^�b�v����E�7�%�\"��#{�_�*@��@ ��<4�4�-��Mi��Q~�-E����L0@�=�́�Q���6t��x��]qx\n�d갱�/����^&�W�%;i4;���X�h���	����j�ת���Y�����i�Ү�~�ш��\$����aD�sD����U\n�D����t�KZ��@|0R	r�t9o�Un����x��h�ٜ	�fLٝe+�z����|�^K�̓�,H��c7<���fNu45�蜭�c̏���k�sg�r���`��hJ���4�s���TE�P4�\$|����=�\$v�\"�#F8a7H�*�KN��*���ɡa-֨��po��ʊ}~�5�R5�:F��ghΏ����X���ю�ɋ��A|3h�`�PwkH��Z�p�0�C��{�n3n6/NF䢼�0��'��-�� �J�������,N�,V ��΃��pJ��\r�\r\np�\n���0�\r�\np����c���4<ס%p�)�&\"�8��v'�DD8/�̚/x���M,�� =\"<f��p�q�&+��,!*��<�p��\0]M��� P�p\r��F��1-�PI������K�	gY��5����e���ѭpJ�0��؍.P�Lyl�q���<3��BZ�,�n�d\$0�/�\"clmåNH��Wk�0� ���~��l��\n�T�M�^�8��\"��\"�GG\\�K	#r@t`\rT'.>DBD7�����M���%�&Ih��l��2�2%\n�z�r9%�S&���!G��5\\Ef�&0�'hҙdTbRs!�pΩ�*CO��2	���� P�rㆱ���l-�,�nem��L� ��J��\nK.=�B?b=H�-��Ů1!j�b>���g50+0�������%O��0Jh(\r�V�\n�\rg�A��d&FO�RV��\r ̀�( ����\r��L�ȃ@�\n���px��5���������6��<#%1O�u/�YmƲ,4�\r�pge���;D;��2p�s����g��#_<A\"���)�i+�\0�kXn�o�\$�%2�F����t���#<3(�+��Ȋ;@Nf��g&8d��f�p��k�<�\0@�v#Ɍb:6��IA\\!�QE�:s+��E/��0k2���L�n���Vy@�����#F�#Q�IS�tQ.�G&Ff��/\0p`ϧ&���fC�����`�0�d?2��&�Qt�Q�DmT��raj[0r�gJETF�/ft�ly\n!\nMf6F))�Fi�n\\�A�k�)a00F�e�<��!a`F\n&";
			break;
		case "lt":
			$f = "T4��FH�%���(�e8NǓY�@�W�̦á�@f�\r��Q4�k9�M�a���Ō��!�^-	Nd)!Ba����S9�lt:��F �0��cA��n8��Ui0���#I��n�P!�D�@l2����Kg\$)L�=&:\nb+�u����l�F0j���o:�\r#(��8Yƛ���/:E����@t4M���HI��'S9���P춛h��b&Nq���|�J��PV�u��o���^<k4�9`��\$�g,�#H(�,1XI�3&�U7��sp��r9X�:9�V�>��B��94-\n���c`�8�	�_\r�\")#j�H��B�Ȕ�C����\nB;%�2�\r1+��-B�6��@���l�4c��:�1��K�\"�c\"�l�����0��\0�;�c X������D4���9�Ax^;�p��#�\\���z��#��.��t\r�*���V���px�!�H �������\nP���R��.b�c����k�x� �2T�=T�.�6�͜�kP���8Ά��\$:�B#�b��*	e��K��;�@�8.�j>��|4�@��ЄH�1�����*@:��bX:��U)K/�4L5�q�ކ�#;�3�ъ�\$���*��c��9B�4��*W	��RT��h�5�\"bT�B�ʔ\\��̆��*9�hm�6\r�[Zʎc�7;������%��4�c��.��f�<�B���t��C����8h�r4?�اQa�&����Ž\r�v<>K��;�\r,�;!�H�!AP��o�K��A�j����3���n�K݂'_(��5��r��o�ћ�a:�>���7%4h�d�B�)�J��r�t��A�^�׼�5����.�J��R9�1�ʍ�0�6G4��D�:�*\r�V7:P:��H�x-�z��K����,��fGu�2��R�	U\"^����-���bRp���u�L���o�r����t��SHGI��&D̚RlM��;�\$�P��xOA���У!`\"й����Oٟo\0��=B��	z�尔��@� 	�4�4�+�FIQR:O��9,`���/Q&5��JkM��8�8+\nC�yOq]Ӻ�b���p.���B��B4�x��t����.!��D8D]�k,�Ӱ�<�!�#��eR40	M�!�+  zX��33~ß*~|�ԑ>�6�\r;`!��\r�^�`\r����Ա�i\r!�^��G��q\"�2DB*P�P	A8�.GV\n������Jc��=��4g\n!zE����.�٠o�l�<����/�%�B�We�Tl�?�DRC�>��C�����~K�VM���8�OK*Ήpe\$�<���P\0C\naH#G�4ZX L�Ħp]E�]#�)��j�H d �@�o�\"ɣ\$����ZK˃HBe�h6ӆ���C�a��h��I�5>'dʓ�^B�ɐGm!\$D��1�5G8�C�G��m�p�P���Q�&����H�{6�'�0�O�dFll\\_zmdmB\r,��\$P�zR�a�Ւ�(��YU!_����c���Y�sf:�3��h�sI����\0�ψ��U��`�5)��oi\r�\$@�^Hb>漮7�A)'x@*D���t-Ս��sJ�ȼˬ)�j�0�ʭ�W��8�~o�sxl���VC�r�,�\"Ȭ&��;'n�1�7\$�}�Eqj`�4G�?��\"��K9��ԙ�Y��[y\$mK��^DKC.P)�}e��VŪ᱆��G�^,qء\\\" ޒ�n�	\n����lh'�\\K�@A�t�dG�sV`��:N�20������B��w���˽~�PL�jݸ�����oo�\0	�^��z��(Jiu��T��W�����9����&����g�t�\0��(	d�@�\0� �ԕ��n� ��S�gH�;g���<��	�,�yS\n�jp�y�2�\$����(P]fJ)�܌�%�_�\0W3��qYq�8U�\r�J;a*@��@ ��\r��ڐ����-ܗ��К s)K�f�VM(A^p���8Ng��3dt��3B5~>�zQ��\nu`\$*�V���)��Ӯ@�Aw\$��\\�q���I���cq]�*X�+y4|���� ��t�O�yO7�+3��{�_�\\3�����9UF�9Ց�C�ّ1:a��=K�O��#�K��P�d�ɺ���,�0�Wp�Ά&%��� V�dx��ŀMg#�}��N��0*��F���-�Z�N�,�/g������Ta������zOz۞�,�_/=o\"t�;r�-0�bY�YD�{���Z�_k\$zS>^{�ؼ�_���z�����b\"Y��/�f5�I�l�;|p���%�p�eŃ�w��F�g�� /���6�bP�\"��:5�8%8\r%����[ @T&�RƯ�����+2q��#�\\T�L�m�\\@/��P8�a.����W��\0���&�����\$������	,0t�%K�^��7}\0�[0�?-��Q��욐��p�ł%�r�B\"�e����<j�JQ�=\r\rlxp�\r��p�d��Rxi�7�:հ�T\"�i,�:�\"<\\�\"#�z#��}�^�j�g�#`�x�����=����DӮ�7��C@���(��b���R�K���'σ����,.r�-d�킪�V�@���ϑ\r�Ұ��M�Q�U�P��^��z���c�0-�����/pۢ)P�������RUn�#\r�����DNj�X �<��R�dZi��=Џ\n���і��	1���\n�w Ѡo�Hkb����c�\"\0�R��!P�TÙm�|��\"�;�2��#��8�o���׍�P��R`@p���rm����,8��ؑ�#ҁ'M��i��;X@�)N�����ABdj� �Ғ�*�\"H��ҹ��-R\n g��0�,>TB�Y�3\$�d(%� r���!M<���#�X���8~�*6q]!�D���XȘa��	��0�Q�_P��1s\"0��,>\r312�61n�e*\r�V�\0�`�tC��E�2��P�j��;\"z�@��*:~ �\n���ps�0�@�\$�,��^�l)�!3��#Ӕ��.~�s��`�#�[İr�ppϥ\0@�B��&\"\"�v�8�Rb�,b�/dp8�@Uc�\0B�3Cڲ��96��\n٪��`޷E\n��Jd48�B%��\"O�|�FXs���^�3�n:��k�h!B�B����)\0��R��?.�*�\$J�4UF/� LBƀ�s\$z2d���tD dm\0�`�/�:E[l\naUqF�1f�e�@��hd	'��\\��T���� ��Db^�4R�nҀ���\r�	�G6=��-�N�'�b�Gl&Ɩ\n�ME\rd>�]:\"�KeJlv\$CQ<��\r�i=͒>��,��%\"��@!� ٣\n2)��O8�";
			break;
		case "ms":
			$f = "A7\"���t4��BQp�� 9���S	�@n0�Mb4d� 3�d&�p(�=G#�i��s4�N����n3����0r5����h	Nd))W�F��SQ��%���h5\r��Q��s7�Pca�T4� f�\$RH\n*���(1��A7[�0!��i9�`J��Xe6��鱤@k2�!�)��Bɝ/���Bk4���C%�A�4�Js.g��@��	�œ��oF�6�sB�������e9NyCJ|y�`J#h(�G�uH�>�T�k7������r�f�\0����6����3���3��P���j0؊;I�Π���::�`ޜ�+�	B��6�A�P�2\r�K��\r�(拍賔8z,0�cL�'\nu/C���H�4\r�^� Ø����`@ c@�2���D4���9�Ax^;ˁr��΀\\���z|����\$��\r�|vԌ�zZ�-!�^0��(�4��D�h*�� ���KÇ\"Pɽ��\rb�	.zh��P�0�MRp���#\n<��MKS�舖7��蔟1��0\"Z|����7�Bu\0��22�PK�#8	���\$RzC0\"@�'ibn0��j0�:L�\\��\$(��ڮ����^�	-��ݿ�p�R��Ov)�\"`ߨ R`�0+�(��w�Rj@��%��)c���ȉ�����4����zţ���~[��MJ���V!��� \"YՒ��@�1\r�ڔ=�w�iQ>����Þ7&K��:�'V�޷��]�<)5w��٪~��-n�0�Pٮ��\n7I��3�l���Q����*n�#o��1�YU�	�NR6�')\nG%&\n0A�/V*D�(��2��s}�s�P0���\$�S���5��`(�H����ARW\$ͯY@74HܘI҄�*J�ĵ.���0�\$�7�\"Ҋ�+L�8'�#���S�e2�z��@���X|�2�2�-���L���B?`��)G�rKI�=(�4����[K�}�&0�S;Uj�e�&���0�:5@�9�4j�3�T`��\r�w�tL#�q����`�hGh�<�z�=�!�:�#8��f9�ȈTb��0u#�	��x��B?Ga�ޣ�Ɠ�s�.!����o\r��RD�<��@@P&�(��\nIш�e���ҤU\n�~:�����0At�F����J��'��\$/('�l\r\$�'4N��*25��5K7��RGJ�;����B!�Q��ȕ��ew0�\0�F�:UAf�A7Bq�<r��a�(��؝����>�P�4�Ĳ���T�rQ6��&�!8:IBQ d��J\r&��v�I0!�l��}0\"7C>�uQ�-�g9B\"��<)�H���'���t��Pe\r8���g��BԤ��@�I�,l���T�'��D敻I�JL�F\n���#&jz2����xd�CF��5`Q�h\n	�8P�T���@�-rX0,�+���V�49S���b\nRI�@� �%��GQl�@�X\n�h@�ك%�ў8�	VC��j7��8�J�肙媟S�A��h�D������&z�q����6�~����>�T+���AX�D���q�Z6G�Bn��)H]�!R6���!\r7b�0�Hb�/E�:��H\n	L��p��i`%���~���P����Epk��:�9�lkY�I 0؈��j_r�\nd̞��OK�l�ؤ��e��j���\"#c�dA\r����n��9>��ټ��AI!��a�8i3���\\t��Q�@<�S����'e�rJ�� �8��HZ���8����\\��܁���K�ـIJ���N�Pe.����6BA2#y��F�UW��m�X�>H����v�	�Um��Щ�)\"dV������Y#��Ԃw6PnfʔJ���^�?66�kx��\r9���#�����@��֋^��`j�{W%^�����YLT�#��L�'~�'5��K�	�\r��u��K�^�7�R߼0G��;���~��ԥ	݆����6IOӼ_���m���-���O��B��.GϤ��B4��\"�<F�r�H�x�d���^~��'�X�W+g!Lŉ�����K�ji�\r�v�LZv#�)\rz.Ќ����'5�\\>�ڎ��y�N���&O��N؇c�z�k�x��t]_�x±�{m�e(K������i\"y�z�}���~E`'>&�(�\":�1B��F<ș3*�yͲ�q�0��A�7dB9�����y�Q�� sН��#ʩW@]��M�k��\$�t���A��}}&{_wn�T�����tߑ�|7\\�����O'�W�\r�K(����Oa:^��w�á%��O�Ng��i(ڙ��[�B>FT(��v�i��O\$2��D�2RNʌϲ�1\0����8l�ɰ��\rȌ%F-�ֹ\"��ZQ��\0�YϦ�P<\\�p5DN\nC�\\��ƐZ����kul�� Y�w.���Q��ǂ\"��Y0b/���̢�l����h��@��s	��	�k��h���M�n��B�Up8����(�0%VG@ƅ�GD:*��|!�G���N��B\$��k��`�-��r(�5B�2�\$�@\"�%c8�\$n�l���ު��\n���p\$����6�\\�DB��v�i�#m8��	mL8H�0+��+���\"Q�\"3��0�X�켂�X�ʝ��b�\$(�����RB\\���\n2���p��ROn_��[�\"b�1�\0�oN��l.5����m��УQ����6&n*�6��RE��Jfu\"h�f`\r�,��'*Bb�O!`@�l8ʈĞ�����F\0��`�?��#�#�z_�V����;�4-�?�\"\$����z���;Q&8�Np@\r��:\0�0�l��㒶`�P�m@";
			break;
		case "nl":
			$f = "W2�N�������)�~\n��fa�O7M�s)��j5�FS���n2�X!��o0���p(�a<M�Sl��e�2�t�I&���#y��+Nb)̅5!Q��q�;�9��`1ƃQ��p9 &pQ��i3�M�`(��ɤf˔�Y;�M`����@�߰���\n,�ঃ	�Xn7�s�����4'S���,:*R�	��5'�t)<_u�������FĜ������'5����>2��v�t+CN��6D�Ͼ��G#��U7�~	ʘr��({S	�H<���\nhk��=oj9n�����4����O����P�7%�;�ã�R(���ڎ��P�2\r���'�@��m`� p�ƒn�@����<m�5�O��8��x���3�(:7A�^��\\��+���zf�t�2��#R��7˰ڡ�+x�!�j��	���.CW+9�jĊ��e:���++ü��ͣ��F���67S��'+í44�p����(�J��C�V�il�BXޗ�b��8C��6�crL�EëT\r��V��0̮0��b;#`��j�,#�uq1�u�ȋI������	!���3%�\"PÌ�#��!i(@�\\]sח#�6�`�1����&{Z9BP��}28����O�T���	��ܐ��K�M�eS�ߐ�P�+� P�2�j\$<6�c�Ȉ�ƃ��G�22.E��5�\"�ڮJ���	���*������ �C�j*`#�\$�0��4�k��	���K��4���\n7\"����.���K3(+��\r�5�&��Y�tpA=���	��O����C��H��Ͽ��Ҩ�̶p�aO+ˣn�@�7�8ɵ����<�㬃�|ʆ�#�4����J�Ĵ�˲��;�q�e3�SL�2,S��8��C�:;����O�<�**��|���G�ːt2���J�c�2��\0�uJ���Fp��d��R�[{��1&G��Bj\rɩ�(d�Ӑs�%�%��>kNl���JV{L*\$\r9�f	�~�͓���*~x���Z�0T �\rs������!�!����Yt.�9�P����EI!��E0��`cJ��e��ڌ��l���G��PC#.@\$#\"!#a��5�S�AUw@)c����b�4a�Қx(h��S6h�!�@�b^E(�A\"�d�x����2*�Q�6\$����T���	����\n4m�+`��V\$g)� �O�X.�E(q�9��d�v-Q5&�䝓�P�,8D�+�6�l6#mh���(L��z�@�Rg\r�SJ��o� �S	��|s(��2^�͉�M�p��\0� -i�Ԩ��=��k.Nșc_=�nFx�)�4�kazI�)T�I��@���^Le�b/\0��1@�i�a�:�*Hb�TZ<�`���z�!5��2���C\0\nzF����\0U\n �@�E�D�0\"�ez��:@a@�G`��3`�tܿx��ۥ����F�m7\"��s�y�n��D�4�k3P�����0�q�K4f�!�4�R��b(i�0(�F�I*�:����bM7���!�2��f������6,I:*E��9��g���1'1cV�cq;���IE�/�2Q\$���\\yNa7Qq؏�RiC(wR�f�4<X���C���VH���B)�0\"��v&9��z�z�]i'�f0��+���8��0��Ca�ʁ%�������{(`*ކ�~����:fB�a�Fx�w�P�BH0��\$���\"9\r\nV\$��r\$@���A��W�ʙ���U����g�CTHc� \0��C\\����&�s�J)#b�7b_�c�b�f#�(p�W9��#�DIg�Z��ה�\0�Z|9���MNX:cs\"�Q�)�܋�KU�10���_�:�K��6z��!<Z�	�V\$�}�\0��3�E ���}�5~�;��-�2?WX\n��Q'�l`K�/�,�GB���RT���2FrWxIU��������AS�*��e4#�mw��Z\rK�U	_���v�Ʊ�k㷦�X�d�6��8�#�l0�S�Z!��*�Oht�<:���ni�ňB'Sa�ʨ�W�� �,5�`	�(`���s���G��Z�ڦlAΩ?\r�v�t�Rs���̎��J�������\nd_�&�͛�v���q�v٘߼�}���^ø}�u�_��V��xV����W�񝛐��\$X�kuF�t5��H�[�p����m#�8����tQ�!L�`���:D��zRY�4z>����:k�m�?~�e�o%Y�� C8X���/[�5�`��dT���+��l6���'�����2\"H��W��|�����n4�\r��#�����j��TfJ9�G�D����nPb�<���_��q�:�0[B�\r#�ǎ����� ��N���Ys/�\\�̱��PL��[l2S�#�ip)o�&]�v�ê饈�L8�T��ĬT�n�\n���\n�R�e9P�\n0�]�'D;,�ADg\niR=L�Ap���\r�W��P`�3�� �Z\nD���!bf/c�C�\"!e�W#G��5\r�ϱ��b���\r\$~!��vCTYb�\nm�_Q>���\rNU�R*p_ �R �jT=�\$k����rz&B���1C.ʠsg� h�v@�\n���pn���\$R&����\r�&� -J��\0�#��ˏ #4(\">\$/��⠶��`������gb��%��l��<> @Q�\r`Db�z�C�6X��Z;bj	�����i���PJ���g#xȦ��#(\$��Yf���:0����.B��2P�RV�B%ß#��6�%H��J��c%Q�(���f*c838Q0'K����C�S\",]�v\"���8�DR��B`k`Ό�E&\r, �2�t#��BE(@k'E�*Q�Eì\r�.�*R%.���bV/��2���l(dl�D�,r*�d;1:\"�}(�\r�Z.��e�.C|��%F�	\0�@�	�t\n`�";
			break;
		case "no":
			$f = "E9�Q��k5�NC�P�\\33AAD����eA�\"a��t����l��\\�u6��x��A%���k����l9�!B)̅)#I̦��Zi�¨q�,�@\nFC1��l7AGCy�o9L�q��\n\$�������?6B�%#)��\n̳h�Z�r��&K�(�6�nW��mj4`�q���e>�䶁\rKM7'�*\\^�w6^MҒa��>mv�>��t��4�	����j���	�L��w;i��y�`N-1�B9{�Sq��o;�!G+D��y�ٰG#���[N��QB<ΎC#0���<2�.[z�?��Ȣ�s�69k` ��jء��x��<�p�:�kC�0�c>�.A\0@�2��H�4\r�N���`@E�B|3�Л�t�㼤1p�.9�@��a|z9�q��J(|6���3-�f7���^0��H����\$b\nʂ\n:<#X�:��+RՎ�H�;�T3T�@���'.#\n��7-�8憌�\0�<�\0HKP�i>%��-\n���U�hڥ���/\r�`֟V�2���69ò�:���3�B2*��S�\0)�5��b������n��;-��̨�0����~�! P��#���BC\$2\r���c��z��c\$��\"`Z5���:4Â.#��C��#�tz\n5�C+\"	�-d�0�������D�+[\0\$����B����eL�H�\0V=A �>*r��/*#D�)z�0��\r&�2�	\0܃N��@)n8'&��\"@�~�ަp��\$���e3p#��+��O�Ǵ����	���1�\\��6���PA^�~�R��G�c5��B��@R��¶0�%C+GC(P9�)H�:B�:�����0iH�4^�؀��c{J�T�1�K�ȁ�bI2X�&��*�rĵ.\r�|:���HD}p%;9N�I�LP\\�x��pZ0n`*y	���nT�Д����j_@\n������ bCH��Ĝ��wJ�e����`lM��&fx��o/��@��!�7�Z�0��٫1�9Y�\$ ���� :�lFi���ճ3�p4�̢���Q�4TDb\"��n�Cfo\$:�D��+�v��(���g\\��ٌ9s)��D����E���'��H!�P	@�G�O�	�����i'����4�E���&M�톖pK�YDƉ?\"\\lOA}wA�<����Pc�w�y5��<�M��3&�?\"��QS�F�7t��@F��4�0�\r���.��=,b�.��7�)� ��Cz�������)�0dfP̒VS�y12HؒR~�C	�k��9Eb�>����d|ҋ��ՉHI\"!���C-�ʡF����E1�&/A��B�Т3�f��2�i��ik}���˞( /���v�L`nHE࡮O	�)�hӔV�	0gP����|��s�1�5�\"8G��\"\$�Ҝ���Ai&`�%�`��g����JS3�U)e!`FpܸL�\n@\r���P�*VQc� E	�ʑ5[)���?�j<���eHY��hZ�|L�8 :\$�h���I���]fJ��PU����#bUY�L5� ��&a��\$1>'���!�h�\\12lٕ�o������*�S�r�9���tJJIi��*	w���k�~B;%<䎔����i�g�*j�y�HzA���T�kPD=����݄K�\0QJ1>��탍��1���(阼1a�	d�~�L[�r����ҖMa�		-�P�߳�,1�P�� �d��)kԾ\"5����]AI>U���:f�1(e�Aa Rb���#�4) R-�\r&�ŢxPKz�\0���#2���\0%��\n��4A9�<%�-b�@�Q!Ȅ���h.��@�Jimb�T�zv�������C���\"yr4e�!�H��&�v��G��G�	HK��܆�4s�`wS�%\n2:G�ԭ[\\���U-��@�P#�`L\0W������lo�ʈ\$�ފ�\0001f�%�>�+�j��'_��(��Nɋ3�,������@R�����X��I���81�u8�_⻌�lPl����S�\"�P�Z�土�����-/�&�lZ\n5*h2P��`0�\"j�0��2\n�@]4���u��Ol��;�u��+�%��-�{tVJ�Ϫ�@\nь-�����q�e�TW=]*���S�SD��Hứ)�vL�o�@�P�s-���V\\ۼ��k����Y4ڭ‟4���������Ft�bܸ���z-���*�v�Ol�9G0c����\\�O���{'W�r�7��+�N�*�V�_�+4���\0����Ŧ~��bP��/����3���D�\n��d��-�|�����J��\$0&he�r7,��-���.Ġ�o��ƠQp\$����!�8��7Kd������s\"S�p�̬PTBO�h��d#V���&V)�n�4]@�\r�t]@����T(�\r�<�0.�:�NB.S2˰�n�\no6��ph#L<��?�rG��( �q�e+�0��Z���;k�/���#P��V�8�N��b��Z�\0004P�l����0�m@�z���0�;�����쯆%#�!.m\n�_��3,�\n�����^gC�Ȃ�������P�)�0���m�V(jPf	e\\T	V��� �E���D�O`Ս��b�Q��@�P\0�`�`\"�����ycJ4�Z5��ڂ�#N�f����\n����\r��%1��m���>�1��\r��c�&���x\"�`�b)p�ͮ��8�J/\"�����H8qΩ.�K:j(m˯\$��\ni.�K�f��Ŏ�#��\\�o��lD��b\n�rj#�'ض�f�R&˚��'ʘ5�Nr撡)2r�f2&���!�l7�F�E��ˮ#2� �\r��m�c#lT_�ܥ\$�2Z����e2���B�ڶ��\nfJ!�G�uRx]@�(���#%\n��1��Ҙ0\0ޡ��-+i ș�:A�}'%!Q�`";
			break;
		case "pl":
			$f = "C=D�)��eb��)��e7�BQp�� 9���s�����\r&����yb������ob�\$Gs(�M0��g�i��n0�!�Sa�`�b!�29)�V%9���	�Y 4���I��0��cA��n8��X1�b2���i�<\n!Gj�C\r��6\"�'C��D7�8k��@r2юFF��6�Վ���Z�B��.�j4� �U��i�'\n���v7v;=��SF7&�A�<�؉����r���Z��p��k'��z\n*�κ\0Q+�5Ə&(y���7�����r7���,I��()���h9<	��3�\$#�R7�\n��7#�ݍ�x��cK��+���5��\n5DbȺ��+D7��`�:#�����1���3��¾P�ʡ\r#��7����c��2�\0x�����C@�:�t���1x��O���x�*J̘^*�^�7�p������7���^0�ʘ�5�)�D�-�9�[����`-.�CB�C�M;�@����Ϣ�2C\"40�H�����\$\0005�M{_�V}\$���	cx�:��\0*#��7�����B���#p��[.\rn9)�J���A6��+UH��P�:�-:� ��(� ��L�`P�2Hz�6(oH�0�Rz6�a\n1`Һ��:R:��=փ�L �8o��C�I����c��orH>n�>��\r{����X�(��Tn;�����=�]E\0N]'�zuZ9�It���Af�#��R>��CL6*���.��^Ao�>5�@P�6�w@\"�[�:*�J垍���j{S!-Y��Đ������@�ˎ�\\�9�s�(����\r9�£\"�>�p�h@�H���:�Ԍo�3� �Ѕ7���2�-.�Ce.�:Ό�2E�¸���3d�niZZ:�0�jʌA��;��S_C��)j��q����O�	W���6��,�K�gE�����k�v\$1����_���/��P��gp5���� �K����G���:%h�v��~CɰDa',�E��ے\n�i-#\n��xoGuI��sڒ[	t2���*gM)�6�d���p\r��є��>4e��	��	�a���p�А����\"D&H\\(m�ez2��R�B%%3�����\\v�,%����bL��4&�/Sz��;F7L�PnOi��1��K�х����H��� �d�F�T�} E����A	aA �DAVZW�@ oEΆ(��&R+5!�\0�hhIh�3��a&Ij�A�%���f �������{��&\$�U8��%Ik�ҭ��ax/Q\$=�\n�����6��C(s6U�x(7\"^LI�vb��#�S.]�pIh��'.\\k�@�)�A���'bgQJ(��4rt,��g	b��@b	N	i��k9DL��C�i�L�54��l�@�,��R0������7�p�ÚUJ�4��]C:b�U�0�[*�X3������j�y)� �%B��Ʋ;G�º�hm�*�A��6\rg��vNI���;��ԃ��/��-La2N��P&�(��_M��V�d���VF�� E�\"�:�O�m>A���:|�ySf�X��2N� 1%pvJ��s�,+�C�<��LK��๪�j������H�ź#W·��jZL�d��^��G,�J�H�#\$wR� �R��|�r<�D<6�`���L�H����K�@oX�3R��!\$����|l�Sn:-��?�7��	ݿ+9h���\nw#��L1��,	��}sΨt:�f��īf�gi(�2�S�hf�d��fq��*(Q��g� *3@5B���)�g���y���ej\rJ�Ƽ�)|�ү\0�3Pސ�yi��J��FB��o�39��!���AC\n@��oZ���&�1��G������y\n���`2�Q�^�t5٦��i]�����B�ZC�\"�:�S�#�h7!����e���C#�7MXЦ��N���0�¡@�Q6��� �ߜP\\�xb���(�}D2���7��4\r3�=!H�ϝL��#[�:|҇P�@�L�_,��l��˸�����T��|g`� �@�BH#���uL{T����T�?p���u\"j��冭��1�E+������6}@�Gc��\$5]��_ewz�\\2pg���{=����^�C����*�?��;2���v�v�<�������x�L�Gv���{�O����Р�;���%�z>�݃��7��7������1�9�իy#du�F\"�F���\$�ʞ�#�K�8-�\n�׉T\$z�d�l*͏�CKb��Τl��'�S�t�8��\rH)#�QI_�G,� �)��y��w��)&�0C���\"����j��%&�ш�8C��L>�	�~�4�|�\n�%js��aFf�u��(0N�(���D�bZp[Pa���PX��p���J\$��\"�&̞�G6\0�\ri4h�\\������\"��KV]b�\"@�W�4�h+A��`0��0�4�`\n�(�o@���w���{�Z��Y��Z\"�\n�V,��%�D,���,�ѫ���l������q�;p�k���117��x� ��`ڴH�9��D�.��� ���J�M��9�M1g�m�E#&�FH{�1�[��p�e����K��e�0Y���*Iq���.�Q���ܹBl�M���vFd�C��al�(+4�	t\$�F_�Vjf��-d�Gb%����Z]m�u�4q�v#YE�\$�@)v-���R!��C�K��\\@��	����Pm�((��l��lm��#�#	p9���%P�QaO�'�����k'�Jܬ_)��Np�.���U#��8�Q%q�.\\�('�V6�o,2�+�a+����T,�,R�%�,p�3-r���z�&HZ�Zc�8�c,Ie <K).�^n\$v�ǔ@�`�n�q]�Q0-��䑛)����M3s����T\rh��&��ј��G*��5�_6ƾ��HET����ჺ��{��D��{N)8�a�_9�m���r&�V�s��tl�'�* \"=R�;�A@�A��96�q�<��=(\$*ӟ4.�=��=O�9㤀��)d�gB�2њ<\n�!���EZ3c;AO�f�/\0�/�#j!Bq�B�= �C�#�7/Ϛ�#6*�\"Xi�<EC�2!�8�-�F���h3q�OF�#(&��R��`�*�>���ei�\$���)ú),L1,F�tQ#�B\$��]i�m��Ii�\n���Zl�j�>���%�n�G��t�o�E@��MN�2Һ~��JM\r�\$Bb0��B;\$u��6�㓌`4�b3�%'�M�t9�H��>�FjC�M�V{0E�9d�5�*\n�OFd�%���#���3�p;��(�U�\r�\$&0��zsQ` L0%�SX͙W�P P�XEO_W��)-��Y-�bpEZՎ��a)��;�{�R(In� 5v|�G�6\np��P��6�p��_��_�\"'O�Y�,T �k�cK\\(1�Ɛ -��u�&0���m�b+\"3��Q\n�-{XayI4\\�G��d�F#X��\0ͣ>e�6�\"\r���� �W^>l!`�q�گ�|;�W �";
			break;
		case "pt":
			$f = "T2�D��r:OF�(J.��0Q9��7�j���s9�էc)�@e7�&��2f4��SI��.&�	��6��'�I�2d��fsX�l@%9��jT�l 7E�&Z!�8���h5\r��Q��z4��F��i7M�ZԞ�	�&))��8&�̆���X\n\$��py��1~4נ\"���^��&��a�V#'��ٞ2��H���d0�vf�����β�����K\$�Sy��x��`�\\[\rOZ��x���N�-�&�����gM�[�<��7�ES�<�n5���st��L@��%� �L4\r�\nh:T�8�s��㫞��p�Ȕ4�T���X��.p�ǉ�\n�4�n�' P�2\r����T:\"m�<� c�<�ܰpP@;�#�\rIC�9����43�0z\r��8a�^��h]�#r.�8^��A,�C ^*�΄�̺'�{��|�B-xƸ�0,N��LJ�����\r�]2�1����+ѫű�U<9T,;#\"�<̶�P��\r�:(��\0�<� M�aX��!��`꼧#J=e��r���L��΍�Ch0��9��\"�0�:���g��%J1�5��e���7��\n	��P�㋆6`���7��؟>�|\rm�(3�x��b��i�6j�r ��{\r�315�7Z��ܤ��&L���YRU�lGIqEWR���焾B�Wic������tS��#k�0\$���������Z[���PŭD�@�!L�>�\"�#2���]N�9g�.�{e�%�(��q:i��պ�o�c���X�R�KŮ���,.�%`U��4m*Y��0���I�\09N.S&�;�ޠ'��Ag�c��؎c5�ak�%.<^�3�+�\n���42��SHy��=�Ø�x�8曧-;��P�h��\r�=�GC��s2CLKR�L)�2�tҚ�hwM��9 ��p/)�EAD���<�1G>xe��p&��0�ĆR�p����hb3�#�]>,BMN�.K�~�D̚RlM����@䝓Ì�5?��\rz�9�p8�@�R��6o\0005�t�GH;2[�� �'vHW�%\$�	O�4[��T&i\n����V��ՂC42}/��g����R7h���\ngP1�%a��c.\n~!&H��@I�Kqa�MƄ|@PH-�� PTI'+�dŇ2<�I l'07' �s߬[#��;��ٍ��?I\0�0��L��9굒�����7ؙ�CzA%�a �^E�p���XKI<�(�]�,y��9��Wl���aL)g�6�&��\"���K�B�]��������\n\n'I �PJE �0�6R?!�A��������.%u�NI&���~�ٙ�7�u���PM�7N(�)�\n��\"R7�X3�`xS\n�,�\"Rx㔸 譙�`@�� u!t �3&?M)j�@�n����0g��j��EM{Ê����]��7�����'���e\n�5@FaȒ �i�#�Sd���2(��L9�('��@B�D!P\"�KL(L����t�sr�B�9X0�X<d� ���1jL朣X0řD(ʎ��4VAh��GiZ�0���(�U7F�G�����43d�Ի�=n�V��sZ�k�Q�����/�\ns�U���V����ҪJ@\n\n���r����Es`T��)i}���`\"�VW1F�� �F�b�3U�@�a�\"0^+1����e��W�7�@�nL�z�K�P�/F8ո�1�Ɋ�h��\nܰ��!u���.��^�[T�~\\�*�@��dj3MDS6�FߞH=O�\$�o<���&3�ᓨ�r\\�1tALRHs��D�+-��\$7���\n�!��@�K�>u����4)�#%��%�`�r��c��p�2��Ƃ�׊�p�@CP�.k�nhf���/6=U+�+jBR���{|�;N���<O��f��l������fpM��|\$9��rR����6�0�O{��	\r��ߕ������3G)\0�r)ˣg�dȫ-�<B1��2�0Ɣ�0�O�n���\"���ˣ'*�1�r���fxbqG�<q7ɴ��S��>��A��<N�j����B���sbf�c�)����!��|�+�V��4M�z�dpGt�v�u���`/3o*cx�ٌwt��G���n�1��\0(� L\$^��>5�Si���I�pqD6��c���1�>�Om����E�0�:E2�_G�VvsB�H�X\$^�Z�AEec���U�yQ%�d��w;��%�3��򿑀/�}����Q�X�>vqc�lp.\r�M����q�Bo�����/�����\r��ܔ�˚��f߮v\$����)d�o�n������5,�\0+�[n���ʷn��`�J�C�?H�3c�d�b�6e�\r�@�/�jxb�k��dT�nY�\"�<��#-�[�� �PRj����-\0��\0:TN��Ʒc���UN�1��� Z\0�W��ZU/����j���ӫ\n�������}�,[��\0�.���#0���i*	m8���� +!Z��d̀������\$���>(�܃��*J(����' :�\$����'p�}��9E�+/FTp�%�\r-)c�yQL;�FZ\n�Bk�q\$oON}br�:���Q7'����O�О%����F˖��b\0���\r����:q0��\"p\n��R�[]q�B��}��0���d=��\r�J�(��X�V�B|������@���\rg fR�r5H�0B��X�3o������J���Yc�\r�Vc�a+\n!D|}`�P�3qTݢN�@��*k\r��H���\n���p\$��T���/���\n��0�/���):�#�<I	rFk���	�U���n�	��t�h\rq��k� ��|bX'\0����PdL��D�Ω�y�jat]���#b�Fh�1W2�6E?�rV!'�F�e1*Eb�k֎'\0006O�0��\\�D�nn~�'q(|�v�:4��*�\r��9�rI�2�U`b`��R�F/3*0c\n\"��ˈ�o�E�'@�T�,Jì#&2�:�2����I6�`͂�C��b�h.�����i+�<4��n�01+�Y��+�� ��.�6*�fr�M5\$K �Xg �5 ";
			break;
		case "pt-br":
			$f = "V7��j���m̧(1��?	E�30��\n'0�f�\rR 8�g6��e6�㱤�rG%����o��i��h�Xj���2L�SI�p�6�N��Lv>%9��\$\\�n 7F��Z)�\r9���h5\r��Q��z4��F��i7M�����&)A��9\"�*R�Q\$�s��NXH��f��F[���\"��M�Q��'�S���f��s���!�\r4g฽�䧂�f���L�o7T��Y|�%�7RA\\�i�A��_f�������DIA��\$���QT�*��f�y�ܕM8��3�@���ij�;ê���B�V�B���¤��+�92�`޿��x䞍�Z#\"��\nKn؎��v���\0�1�I�\r�1B�\0�(�j0�p�;�� X�`�ь��D4���9�Ax^;́t7�ar�3��X^8IҀ�2��(��7��z���^0��3�1�,c\r�@P��<��n��C�A\r�4@�%�\"7LST�MJ��pޯ�M\$\n�\n�x����(�C��U�lہB�6�\nt4�5��A��*7m�#����j�ƽ=�0�:�!�`�CkD:��`�e9�Zבh�t�u�ӌt�(�0I�\r�	�V�C6kn�:7��*W\nw(���� ���6%2j�i��*���&L[�>�c( �([�3��F\"��B�6���!��}�����f��5W�l4����F�#lp��B(񫲚\\�Hlh\"f��o��YC�,��ϸ���ŏ�k�A���3/D����X0�՞Ӣ�#}&;�/���t*�Y+Ў2��L�mSX���x�3\r�\0��,�t7�)��X#��6��n_��)��x�3�/���q|�2��S�\$��h��HS\$ާ\"�\\�7�`:�q�[4R����)����Թ/L�;��wM�sh��,\r��A��o�J�P���R��)M�Mɂ���2��j��a\$�&��4�IrVp�P���V})m.���*g~i�9&���I[�:	�;�P�m`�t�@�����\n�b������T�P'Ȩ���o�a�����!�`�Ω�M@ɔ���;i��C��Y��0�h>�Q�wI�x�p��Y�\$ѡ��K�c�#&t43ߡ�n#L:��nn!��^&�(�����7\0���TY�q�d���bPNU�nE!��srH\r�P6�ͫ���[?����TC0id,d�\0�P�+�'Q�=(��R{�	�8\$�@�ҊF8!�4D���|�d/qӺ���S\nA=��C4I� CĴ������(�v��!��f(E�\$���Z�\"�D���􊡨A�@����rp!\$���V�J�4\"Į%ph��f�Ɯ�=�� �c_��\$\$��hP	�L*3sĉ��Q��3������ԇ���'� #FT˳6|s&	�	�MM'd'�ݯ�6�#�d�2K��K�\0F\n�|�+2����5�ҕ�\"N���\$�`��H��c�\0�0\0��P�*[, E	�Α����z�XJ�A�`�K���癇���������.E.�(�G*u���mŰ��\"��^�IG��U\"�jD�t�*�����ͮ��\r�S���m&~�3�z�o]�ij5�Tv���Q�X���BI��Yr'h+�<G\n�:���H��S\\c�&N����*�\\��.C��t�8ͫ��fa:1f4��0��\n�j�qYi	�e�3puMH�_	��e�(+�Ŋ�f'CF����Y��#��'#�\0_j\0m=jL�1|������护Cu�R�S,T\0��s\n�l���ؤ�N��0�������9 �IΊ�qM1�T�Q`T\n�!��@���3t���1�#��u�b����\\�3JyCz�Vz�U��b����*%=Z��12ǹF���>�^�(���g�5������%F��8��yb�����ɮ�bLZ�bR��N��1�X8�`� Q��u|�PC!��Z!a��Ӈr/���&ex�p��Q�l�,���nb�&黩����G\n\n/�����?ay0�1DV��6� +�0��ݺ�6Ɏ)x��ecI�sX�%\08�-�/�_W�(db��0a>��O�6�0�0����}���b��וO`\r�ϱ'��Fn�;�)3���`f�A����Ǘ��{�x��HB]4�b8}�v,f���4f�8@�&�Y��Z�;o��8��v�+�j<�S�>D�+��n2fUFoh�#�\0j!fė�,kT�A��\0��,�Yw��g�]�0�o{/�⼆��,�������^����x|)�T3K2�(�!��ۧg.����8?�B�m�^'��[Ka���O�6Ω����~���̩������/\0���\0,��\n���(-�W��f�/��%c�C\"�˦\$\$�<.NY��(�\nX��Jh����*�od%D>�0>��,bPR?��#�h�/��o�Q\n��=+��\n���̸?�hk�2I�d#6]�@&%/�ȬO��A\rC\n���\r��3��o�ѥ8��>�Z��λ��o���s�悌о�	�:���+�k������@�(�z�DX#�8��&�6�e��-IP�/��P�fQa�����,�&n,.\"��v��Z*:�z�0�J0�\"�\n�hfžoF̫����͂�����l���@����gFd&���x�L���\n�X�&bμ��BB��Q����� 'd�1����1��QbXIbzL�ш3er��\$i��%4E��Q��K�D*�ڥz_��#b7#0o<2�ra����#�f��\$�u&�Pc�\r�V�@�_B,\r��OB9qLۣ�2`Z_�s�>\r��F��x`�\n���qŮ2L�'-~�O\n����A	-�'ok-%	#�<\$D\$�\\%#0�,�H��E\0�!���/��\n���gB�C�)��I�@���\"g�@[�/�rDJ�O\$4�=,C�\n�D�atS\"��n�,6>qIP<�N�\$ojR���\"�7��oh!��g�Cj71�LZ��k7�p!�#�Dz��9^;Ӕ?���*,`����1\r쌷`�{���B��s����J��ܶ�~�px���h��]\"8a`�4s�l�G`꒦;��ct���3/��82��X0ĹE�B�bIS&���Q�W��0k�@�/�V*�;\$�G���\$��@�~/��";
			break;
		case "ro":
			$f = "S:���VBl� 9�L�S������BQp����	�@p:�\$\"��c���f���L�L�#��>e�L��1p(�/���i��i�L��I�@-	Nd���e9�%�	��@n��h��|�X\nFC1��l7AFsy�o9B�&�\rن�7F԰�82`u���Z:LFSa�zE2`xHx(�n9�̹�g��I�f;���=,��f��o��NƜ��� :n�N,�h��2YY�N�;���΁� �A�f����2�r'-K��� �!�{��:<�ٸ�\nd& g-�(��0`P�ތ�P�7\rcp�;�)��9�j6�I�f�\r�Bp���K\n��@P�0��`�L#�1P+>:L�7��\"p8&j(�2�L肥�i�@2\r���1À�+Cƫ�hK�HlS\$0�!\0��\r\r�������`@%�C�3��:����x�;��R��Ar�3���^8L3�2��\r��p���:\r.�x�!���6��C��)�<�D�h�̥�C�� �<o-UV\r5s�ɍ����\rb��AN�J+ăr�3�h��\r���:!-�h�h(�k��0���4����Q �:ۏ\"`��h�Cs�m(�2��\nj�����t����m[b��F�%���1�,;�&bL;V�5h|@�)�����E⁏	{���2�blȌL���ΐ�&��9 V41����5��V��!ň�i�SV4�-��:�Ư��������pʃ��(7M�˒�bH�%�C�:\"��魴��(i�^���в�@\")�Zp�Z\n�y�**r�ʄ�R�z��)��	]Eia��ڔ�B\$������_?��q��B8�s�mh6F\0Sb��ր�3\r�\nz\$���*\r��t<�'��̡����3F���a�k(��aJ�m}^�5Ҵ��枊��S��^�H3�~�ɚf;npÕ��R�M��8�4�úyH	�?��zD/�P�(�}�R�R�e��@M�		 !�����	�L�>�3���S�E���I�MK�0%�ỡ< �3���ӂrN��<'���S�P,��J���>nA�ʮ��\n��C'U��CZ�+H�9#�h���f��2� �L�A�%�ܼ,���S�u-�ldɸr�(eg���P{ρ��q��h�eҞCwK��4�e�9���)��,چ�\$o��d�9��\"KnǨ'乃r;�iL����n�\"�oE8�r8̐\\SC\nq�!ǚ�ܻG#j�ij�ߴ˒���X�!W}F���H#څL�h�4G��y�'�ү�BOBS\nA;G��y	F�*'��L\\�\$����QJ9m\$�`��Ffg��\r4m��\"VH�s��%��_�Ry�\\<�\$�V|�4!�g�s�y�X+A����J<t��,�✡\r�I+�50�T�h�����y)�d��H�;pd!}BRK��#����\\B��l��I2vA�ƨ����ſ']�\nl쭳cvK0T\nr�>��US���9��������������FȜ&��2��������L���o����%��dѕ�3�ɐ-]v����u�b�je]R�\0�0���S�~n*E'��t[>�����I��N)8��ܭ.�gC�0H�����}\rjN�ț��V�xAYuK�*t����mx� t�a��\$�\0�hʒ~�����nh>|1�iV�� ���%8t��[�ͩ0@�!�?D��ģ�/�9��2�pG��eVk�\"��=S[mQ\\�r�a��j:D2V��.l�Wt:�����E�\\+�N�`�LR�a��?��0 1�m�J�p�����W��m\$������R\"A�\\2f�`KX3��SRv��s����_İ�J!!P*��?&S��t�p����L�0�x/eQh8�A�)cXkUg��k�PQg�`�)�\"3��Ը�[���W�.;����񻌎�*;��nwG��j-����:W���h'��z�6���+}q��uw����w�e��FT� ��V�WD��\"�`q*�eo��G�䥮4&|G:���M�e�'�s�9�q�P�x�+�\"�3�Oa���iEuݨe�Y/�n�A[����ٽ���ͦ�g��6�*�0�R���N)b\n3W�6��n܇�+G���22�&�J�`w��J���:y�y�Io|���Һ�xnS'�UJ.\\8�y�{�`΄D�J��S�&�յ�uɊS��2唶���k����MH�(��{����!C������u�B���d0����i�����<\r�pX*}�\\��濏��L�*p5�Y\0L��y\0´�o����G*: ��l�n�V,.եD�.�����U\r�0.�ND�-:�c�U@50D5��L����(l��h:����F��&�c��pH�opt�Џ�1,�����H��\n��=C�P|*�*�>v/¥���<���,c���Mc\\\r��c�;'��������D�[�i	���px�o.�)�;e�lF�kL�G�Ϩ�[��3��VEh�oD���L����ej%�~��,�j��\rx�eaL�@�lG�L OP�L�.��reP��<���oT)F F�[#����ƶ�q���BOBB:@�aDC�ob~S�\$:c�:�Z�YF�a���Qo:��-Yq:�-^TQ���\r`\$�U1�WP9%D���\"�M{��F#Jw�<�E�/%��\"�є�z�R@��o��.��D�p�>Q�!#�FBB�bo�m%B\\f�Qn�h@�(��\$n�?id\n���A)��[)�:�� 2d'��K��1��:\nr3D\\ N�W\$/T?d����!b��@��2�,hFq��g�Gc�X�/����G�m!%��pd�CE.R�	S1m��\\5�F\0�m*W ��p�-F�h&��z�'�~f�ڀb���\n���Z2\$�>�.Y�䥅������x��|�#,�b:#�B\$g�c�G�f��j���̄��<#4bOo4\">[�B ��琬�����,V�p !�bzFb��m�8�x��DG�w`_b�D�+Ed_cW�0W��1��2, �4�l�oLS�(�Q�ƥA40�T�8�2��6�l2j��d� �B�mgm@/%�_NƼӤ��qF�8���e�R��t��`�b:E�d.XF�8�/`@�ZVp,/��:%� DJ��B��:n�n�4��\$-�0\"��f�P�jt����@\r�\\��mhx�Q�8\\��(`�	\0t	��@�\n`";
			break;
		case "ru":
			$f = "�I4Qb�\r��h-Z(KA{���ᙘ@s4��\$h�X4m�E�FyAg�����\nQBKW2)R�A@�apz\0]NKWRi�Ay-]�!�&��	���p�CE#���yl��\n@N'R)��\0�	Nd*;AEJ�K����F���\$�V�&�'AA�0�@\nFC1��l7c+�&\"I�Iз��>Ĺ���K,q��ϴ�.��u�9�꠆��L���,&��NsD�M�����e!_��Z��G*�r�;i��9X��p�d����'ˌ6ky�}�V��\n�P����ػN�3\0\$�,�:)�f�(nB>�\$e�\n��mz������!0<=�����S<��lP�*�E�i�䦖�;�(P1�W�j�t�E���k�!S<�9DzT��\nkX]\$�(���!�y&�h�0�2����X���E4�\$����n����)�56d+R�C��<�%�N��E���3���# �4��(��<�\$5BϤ>�Bnrb_�E�V֖�S�� M�V���<*\$xX�@4C(��C@�:�t�㽜4�1M�x�3��(���9�����K�|h5�ihʵj���)*��D2�\\�x�.��#�Ӵ�ֹN���	���a\$̙,�dO!��iDE�dn�G&�γ!�6�]�C �L�(�Ic�H9��?��3Ά���7:�%V���N{���օ�d���k�⌮�~��Kʟ���ʆ�5� �ijt��\$;�7vo�L67����l�~Խ�*|۳@��\"]bR&)�{>��3�����z�D|꼴.3GNdvJ��R�Dc�Ba�OT}6#�\n�����M��{���m���!�\\W�!������%t�(�9˞ݻYA\nb����\\\"�#)+��\$\\��F�,/=c�w�i2	W%���Ac؆C���%��P��)0��,��^��4�X�x繱V�M��)�ё�Zp�da\"	�#`��#�?�9���ؠ���	˕D�V@D\r!�0� �K�sX-�k\r��;Se���趃�i\r�9��^RÊ�H�\0004�O���!�x�\nk��E���M1�,hq5��&C\\�=ki�6@��,I��3�M_�Q�TDochs�y��|y�q�0��=g��UQ��)�����F ��xH#uC*ա�#�C��`���RċlP0Y���B��t,*�1�9%℀S��N�\"���D�h�%V��NO�)E!���;\"�,I������K��#%�F���7�%f9�K�)	%�=3Ћ��h�G�s6Q�.3uw�C�b+�\"J���a�NQ~Y�#�x�����V>L�Z U�UK����9'(-�'�]���BǹS�z��\n�X�d����֍%Z�Yl-�^\"Xa�)m�%��h>���9��rx�,颙�Z�TzN�(��b�.���m�S?��L%\$\\=4E�&�*4\nI�Gd-�Qy�G�Ue+�N�u���\"�Y)f,���&Z�]l����EL\\��h�H]�Z�sR���c\r\$9�y:�W�!��G�UID|*����z�\\��l�dի�B�Q�\0���z���Œ'XYJ;/�e�('Ď����gM���l�YHm(�I\"��weQ��N�ۢ�싩�<��D����N�\$�!Sݓ����,��*��\$��\\�#�衪�zd�(.@����?rF��n��T�B���zC������k���cP��HLb��\$5����G�j�>�}5Q��[����1�1+v�J��(�Y�˪��\rub}A���wJ�K����Y.��\\ٕ�Gc��w�^@�7 F��R��֥��3T9�ǧB~\r�P�QBF\"G�����d���Ft�J�R����%��t�dҫ9�K���6���T(�%�Q*S\r�]����r�_�a@\"	��٠I�Z���N��]->e\$��\$��g��췑X�ͧ��pBFW�\0�£�U��f�)\"&��*k��3�4DU��[�@�rLRn�]{�&��?��Iy^4��`�8T�*�kLOq5�x��u��\\�&A˧o%]��^��-b��\0�����O%�4�ӥlh8�[K�v�d+�_ȝA�nVjHg9��v�!�~pA:���#z�o�L̆E��R#i����*�ROd}�G������J�8]8�&��~�)L�����!�9>����.�Q�[��`p��1�=	��J���).�� �o3;�aPd'��B�v�\raYڐ�xX�r	d}��__Z��r��P �X�L��y�ƧB�L���J�!A�A�@\$�	>\rC:�.5�z)�����'�,�E�f�q̆��7��:�A�~�C����ψ��Mp|oJF��n�O֯ƛC��M�.6F���,c��-@�b�W�n�d��\$'ʸwBa�@|��pN0g�c�n�(�LLΆb|��\0H�=���B��k�)�&�Dj�/Iz�PbKn���@��/BЁPf�0\$e���������\n]�>nH���&�2��b�mG���^a��A�p�0��ATC���+�n��/N��el�N��\n�� �	������&�L,QK�&�N��P��J��s z��\$g�^1�Tʴ\$�ک�1_��#&�;H�̦&�+�z�\$�|˰J\nOt0a�qn��O	��F��������rz���q~�1�F����r������e�,h}���MX\$q����Ѹ8�K���(#��cܖ���(*޺Ɲf�2\0�� ��/�C�ur�� .�\$��rdFbv�@�`�Ql%�&\"X�NT/�xv�!��%\"C%b%�n/��-��=J��\"�¶0�晄(2h�H��`刈v�bHLi�c��3��F'2�P�,�Kdmd���J�Ơ��>�2��ЊA1n����G��8N,�c��C2l�J\$%/C�o)���`E��0K3�N��H�0,�&�.�B�FNO�,��R��3�[3��=sB/��s4��y�@*�X��@��bd�yfL�B�)�P�33��F��Zo,�o�\$u��.B\"��Z��M��O�a8�tI����L�����B���>�%���i�-&��pLw\"���>��PCN�\$\0R�3w<&��6��5i��/�2�`�l�L�4JC2JF�YA���ߧU4Sl��5BEC�=q�5�6p��D�3i3�E�����f�Wr0Q�BsY>TJ	�F/!4g\$�FÂa�AGR&e�*�T��R~t1HtcH�7L\$�oI�aIь���G�}J��\"%DA�2l�}4m3ΉM��MΒ��HGN��\"��CN>�BU�:��Ը\$*,.j�S���k�,I�8�-B%�%�bb���a�m͸���!j�!�̶P]?�0�T��\$�4?Lh�U0ibmˡåN���V�>�gH0��0е7�F��B6��<�t�d���Ќ�!f:딗�4��L�#,:f�3A��\\r5A���u�1��Jo����^;6��]�]Q\0��^�c]�]�z�+���O�� h;2��]��P��.����0�S����9bV<�)��|j�\"!A\reBBg0�\n˶����E����@M�Z��KԖ��gH��0�VfP�g�L�Q\\��A�{3�+hUK5�6��c&%+��p�uֱ\$DQIV�&I6CkBp�>o��l��h��a�d����Nw�	�	R�P��Nt�.В���!t�M�paEp��qb�cc�~�[oЪ���E�qq���\0���VHGO*A/��O4�0�!�]WRt�p�i5��vN�!v���worWtK\"���Sh��K��t��x�+2�l�}H�b��ы��t�2�k{��41{v�d����\$w�f�xʷ�n�=��j��bNuEÛ*��s�y}h�r�{q��ɗ��Lt��7�w/tw2G{#�0dB8h�\r�VS����{º]a�e9��V��,%N{\\�Wk,�#2�'�X�Up>UV�����mZ\n���`qr�l^UB��ol	����ωla�d�����X�lUjt��=ы����}�{c	.��V-�wb?�ck�x�'�|��p6Cx�W�sr�\\�Wۅ���n9�����XF8P`�\$h��v��Av.��'e`+�5f3&��&P�L(�o�>��)fl�!��\$��q�D�ƴ����fOt�H;UH4�aG��b�R8q�?��I������Q�+�-9|y�����>Y�5�Ƶ0��Z�'Y�3;8�\r����u�	>r�3S��6C�~�Mx�fNP��\"��t:�:\0�M�>����@\n`�Ko\0�\$u\nD�Pm��\"�\0003��>�Z�?�OG�G�.2\0Aj�G��f�f\$���}�dPs.a�]YH�t~j������:Bt�z�-S����bSZ%n�0�46��\n��fՑ*TN%������";
			break;
		case "sk":
			$f = "N0��FP�%���(��]��(a�@n2�\r�C	��l7��&�����������P�\r�h���l2������5��rxdB\$r:�\rFQ\0��B���18���-9���H�0��cA��n8��)���D�&sL�b\nb�M&}0�a1g�̤�k0��2pQZ@�_bԷ���0 �_0��ɾ�h��\r�Y�83�Nb���p�/ƃN��b�a��aWw�M\r�+o;I���Cv��\0��!����F\"<�lb�Xj�v&�g��0��<���zn5������9\"j��eHڇ?���\n� �-�~	\rR@�n��0b<4\r����p�991	R4�D��#( ��j�� ��\"�x�5����#��Dcp���0�\0000�j`�4��C=\"E���;�c X��H2���D4���9�Ax^;�r�#�\\���zr�Ը9�xD��j&�.�2&���H�7�x�%\"��8<q*�2&��7��c����@�:\"\nC�6�\n\"44'��WV�m���P��'h�v5â�:7<�hJ2:6=�e�6m�e\rMh��!t8�*R�P�7ՃuPP�b�քH�1�C-�:C �:���R:�T�0V�L��:�c�����o�`_/�P5��*������#�(��C҄����J�Ŵ�X\rb�kFc^\r�c`��0	�1�#r(��b�V���:&��|:��&�V�l6P�U�=\\�#����-�.J ���ȧ�=z�\0�� P����1bC�H�!^�(�>{�Z���\\�;^���;R��\$\"6�ʘ�\n�s�)�Z��e��GG�iH�2��d��e26q��6Ɏ4�O�I�۶�K�`9.8�r�.|�l꒎��x�3\r��R'��qC���ސ�cp�`���#I5�\$��9��/�0��\n�}�t�u��@����C��\nb�CeD�;\"��f��)\n����E�IIh�:��\0ib�Q0�ƙS:iMi�7��ӲxOA���f�Tz�й\ru\"�ʊHQ�����w�\n_E�쬔�JЊ�v��RJ�)O��-�����J���0&%�BjM��8t�aBwI�=�����n���/8n�� U�T��ǰCZ�G�p9=�r!�T�m	y�t���#\"��<��M���A��\$X`�6Y/��@�X�f6DQ�>�����}�M*��8EU` ^zC����L�{/�`�ƼV\"2	�ĥ�tVM|�\\����\"B���^�)��0������\0PCQ�`1�ԕ��#�Ԭ��jͪ=H��7�v<JBqu��iD4�X1L\"p�-�8�_��\"��>�w���\r��u�����@w6�yt�(�Lg�D2�\n���~�'�!�0����:��*�L�� ��\$��\"o# o8�d^ǢXK��2{dj��9.N&��Wǎ.��/U#�@�%9�RHXy3��\rO�NVBU�+�8�²2\0vRR���Q���>��R�<\$�\n�O\naQ�؜�n<4�ʊ�pI��U~���6h�� (!�30�qDhe\rU�x�t ��q��l���@_`b.`�)�\n�<��{*��A.o�ו�[�A�%!:�@�3Y?\",�3�]�L�m�8P�T��@�-�WE��Ų����j��>�X�IYP�e�t+T�}%�9@����S\"+/0���\n9h�6+�t�Le���P�\",�C%(e����D�k��g794|�F#I�Ū5��ߎ0-�卻P��XoH���a��#���1���b���G��@\$\\�8��g�\$�Ld\0o�9�#Fb�b\r@\rɸ��	�m�L4��e:���A�v��ݎ�)0X�0�ʹ}�X��������0m��v�fNub��S�D\0�J1Bz�<��8Q�S�����#V��96���#��g�����h�4a�٠\0PFQjue&<�[hU#�F��,�ZI�#A���#cP��\n�!��A]O�1�TB�\$\0��'��\r�죔|{���yg�t֑��F��₵�A�h�2\0�foR�T%�S\nj��mQ���Z!��\n���&᧓�2��w+��ߗs6���5��X��B�9�Y\r���lr	Iy�F]�slH���Q��������3�+9��˻?<q��������p&���Y����n�Ϻ�N���\0�4�N�QJ����\n��\n=���.j�����AM��9�VL��\"D��%�G�u+(�Ҡ����x9f�]p8��\r�(��e{���BA�U�̟3���\$���Pt꿒d�쇓�@˵��x/�:Â�é�гl�~/��1��7rdP��P>��#x�IO��O���,�|�����Z�.�o���FD��l�@\"~o�\nͪ:B6��J��G���\02 �� F*�Ov#eb��>��m����\n#p^Ĥ(��@E��ln�nz��R�E.��Vl��[�Ɯ��eo�0ʬhp�\0Lt��-��Ȑ�G�\\��\0жC��q��\$0�p�P	�n��\$�\r�S�t\$�� p�.�H����nT�̞�\0H����1/�O������匞0�_m���\0qu��Ђ����1\0P��mt׋�C�����0���H��L��P�U�{�v�U�R��i*�% ����\rExEb,�\"��[@��\"�C�(��&`�ڦ�W���.K�fY�ѴFњ���\"��(Sd�.�ة�T*��C���VS�<\"Ѣ\"®�H�\$�q-~�+W�Bg\0Xm:փ&��n9b�źӭ>Zp���]\"�c#n�qX��o#�!#�6k�Ғ�.:��(B�R@ǰ�C�&�\r�4��]&�'/�\$�\r�<��|1�Tl#�)1�n��48e�Q�b[��\$�|S��Ӯ�j�'Qt p4B-��S�W&��R���'p\r-�+҃�'��f@�Ɇ�U�)����|F/��)��-�\$n\"JZѱ��D��ؑU&o�#s,�b(�l�M�3|��Pڱ�0%k4m�)f�5�XAgRvrm)�A�|��4��2��5\r:ݓr���w\$d8D!7s�4��8n4���.�:A�<do�1��{���0�1�T�Lr�����,�N�5���S���e�\$�bE�H��(\$\n��d�WƚF ���B9�&��лeJ\r�V\rg�?+8�\n���(\$�T�&��H�e#\$% ��	\"G����\n���p?��#cΨ���B&Z��ꎄ��'�n�s�y3��t�o���o�')(�#4#�<\$�_�%Th��\n���Fm�^�f@�	�\\��6E�\$0<�\n�79��L��%\0	�޸�O4�c�[{!B4bp-���0a\n�Q=*���p�R�X-�\n��-G���'�&(~�0,\r���n�p!2�3Q[T\$5c@'�F''*F��uVpGր�/K��\"�'?:#�\\\r�\n�(2~���Y�'�*��ɤF�T5�\nN'K��^m�p%���\$\"g5�1���eJ�c�1��LҕR��-��^,@�lF\$\$n�l�j��_�4�R�T��2c�?��#�dA��g����{a�bvB	\0�@�	�t\n`�";
			break;
		case "sl":
			$f = "S:D��ib#L&�H�%���(�6�����l7�WƓ��@d0�\r�Y�]0���XI�� ��\r&�y��'��̲��%9���J�nn��S鉆^ #!��j6� �!��n7��F�9�<l�I����/*�L��QZ�v���c���c��M�Q��3���g#N\0�e3�Nb	P��p�@s��Nn�b���f��.������Pl5MB�z67Q�����fn�_�T9�n3��'�Q�������(�p�]/�Sq��w�NG(֫K�� �(a���֘��y���2�B;4�B�0�B�(�0�\0*5�R<ɍ0d ��j��\$�{4ȧ�>�'��1�C��&�\n�0�h�\r\\J�����`@&�`�3��:����x�'�ʹ4�Ar43��(���v9�xD���\n���ڔ�#x��|��k�(���\n[��X\$���֌��)��+��<;.28�M�.���'\r�&2�#(�\n��\r�:*��\0Ę��MQUU�\r]T�cRKY��2�%C`�2�`P�4�\0P�7�k���## �	2Of�����B\$�0��bk\r��:�K��:��+\0C �:���:�J�5�Òx�8��K���b7ڀP�4�K��7��&�*�͟k�8�63��.�h[�?7���&-C\"mc]H�r�MU�T=%�\"�E�����;�9���Mu��\0AN|P��{��ZN�����(����\"@P��\\��K�(��4K[\0@��2��MÚ^�if)Ay\r#f�����h��#��CP*�Q!��dSÒ���[�TJ��3'J�^'��bM�C{%5���=���<R3[�@�����9t��0���_)��P9�)|\n���9��<���s���#�=T�1)�Q�Ǹ�6w� 1�,�#IT�'J�7*��̶2M�d�1߂<�Գ���bj*x	cz������-ش �(�	/	����\$���<l��8��\0�B|i\$����úQC/�+\$����mKM�-�0��Hm]d��|������T���Q	!=N�-G��O�7D��`@���b\$��d���key��2J��3b������Ha�T�:�Qcu���3F@��a?��1���H�r@�޿����u9	�<2`�ICB��j)�@�\0()\0���d&�M�o%�\r7̊��2�aN'�.f�* E�\\7�u8�֩vA�@ɴb\\���g�1Q.����8pw��%�R� c�(h3�x�4�ǘ�\\��Ĵg��aL)h	�7��F����� Ih5�؜L	�4�X뷂@�eAF!�d���J1\$IjU@C؊��	S4	V6�D�ɋ\$�v]#�M!� ��q�@�az�?���H���)t̼\"\nNI��O\$��I�nxS\n�-:@�u���4�I�vI��V����o	�H14����>H\r�,UE�2 �K��0�#I@B�KD�̟�����3J��`�n��GR1Bȝ�\0s�f����̤�8!��H2|�����QH'��cA4����a�Y(7uuG�0�R�'��E��nb9�~���b`f	�1�M���ȡ��f������yfg�Q]�b��<�2a�;���Ct���X�z��6�\0� )��#�\$R��~  �c�q�'����Ù3P\0(-E�b���2���	Y��\0\ne�` �xripN\nȂt��I���>(�8_0�TA&���^�͑nY���6���y\rL�E2�bLL�ܐ�Yk[��/�����{\0l>�&%����q�:qHź\\�PFMD�>Y���^O9�>'��w��<'蝡p�C	\0��.T>�i*#_���%��N�[&j�*�^Vy�0�u\\i�J�����P����⫁�Y㼑Cu@doH�+�[l�⶙�F�\0��uU9Տ 2��%�u��)ϗ6ϊu����7��C��غ�c��9�u���R\0�k�6K����{gblcg���a֛-�I�s�4�s\$�Ր)������/�nn����z��Gx*���ut\r*�8��I�y�����N\$d���%T��8���[c��bA�����x��s,�\"�8���J�b�#�يs���i*f/�B�K���ض�>���P�|�2�ӥ�Gg�ϩ��؂�cC���sA�H����<�\"����\"��0y>/k`K�%b��ND.��Ϋ_\r��©�dS�R�>1N�F����k	x�tÕ�*�st��W��%��nƢ��Jt�f�L���hf��)�^IV��\0�OZ�q:O	����HL��,�9��\0�^��|Ƙ�]�z6c�X�� �h��v�������P_F\$\n��yaQk<������/��ON��F�t�@�'J��(�c\"%E~\rm����-���{�x9�\$���lَ��-���80=�Bo0G�J��NKM��m<0���e`��L�M�PV0��L��!�/@���7p�!/Lޏ�����\0&v�Nh��r�̄6K>W�ҋ�S�L=갨`訣d\$���`�*b�&�J������6�	�ғ0δ1��*��\$Ȱ\rC\$|C�KG\r��\nl��L�7�~�L�Rl���zW傞\\U(�\0��ť\nC\nQ\\������p\r�Ran�(q`��o���W��Lv�'�\0QZ��l�ѐ(c�������%��MŀO2�f��Kw\0�1��eG�H�?/�1��ؐ�ͱ�L��p\$�X]\$*fK�\r��qr���\re��jPgq�����0�X���#��\$��)+���'p�8 �=\"�k\0q��&��J;��!��%���p,����rL* �#Mn/�ϑ�Ѝ#�'RA\$�Z�x/cb-1�!��ʉP/�8Ɔp����~��Ǝ��+g\0Bn&�#؎�:�����3P[,R��\$���_�ܱ��\nq�K�vU�+���So*�rT�3,����1P�s��1������,S�2�gf\r�V��@��'�~���7�(%���(�i�6��\n���pl��{b�T��i/E0�vc�8�ٰT'\r���8�p��7�����+ӆ��#m�:��c&�@X/`� \nOY! �ΓV��r?)�7�&���&k� I:�\"ܲ���N0�^	�ޭ\$� D\\`�7��>F<�\0�8\$b�̢�����f6�B(��b��B�rRKv��Cϼ'�:64F�Ps\n1�E#PC�2���\$�4��EI�Q��E�)*���2Oho@�Ť�/��*\\<	7IBt'�\\�C��ਫƳ���L\n�L�����FƷ/�\"�L�\0�+���5�\08�4��B&/�#\$�ދ�*7D\"�X�ON�6@�2�;�F;�4%�pE&i�";
			break;
		case "sr":
			$f = "�J4����4P-Ak	@��6�\r��h/`��P�\\33`���h���E����C��\\f�LJⰦ��e_���D�eh��RƂ���hQ�	��jQ����*�1a1�CV�9��%9��P	u6cc�U�P���/�A�B�P�b2��a��s\$_��T���I0�.\"u�Z�H��-�0ՃAcYXZ�5�V\$Q�4�Y�iq���c9m:��M�Q��v2�\r����i;M�S9�� :q�!���:\r<��˵ɫ�x�b���x�>D�q�M��|];ٴRT�R�Ҕ=�q0�!/kV֠�N�)\nS�)��H�3��<��Ӛ�ƨ2E�H�2	��׊�p���p@2�C��9<12��?�b0��Q�ȧ�sֲσT�\$�R�&ˋ`Ϊ\n�|�%��8�	!?/,�n�LS�� �L���� ��l% ��8Cx�:c�g;�#��p��3���#��;�.�w>8�H�;�c X�(�9�0z\r��8a�^��H\\0ͳ|i��x�7�%JC ^-���0������7���^0��γ���ʋjh��#,��!���]\\(�\0��T��l��]-����򽢂����)w���¸�9\r�F��#>�N�(��a�a,�\"����>S\$_�R:�^�H�HH'ixZ�ˈ¾Dd�@�N�#��;���:�����ZMy�R<���C&�3��܏k�+��u\\9s',�̒��w��l��C���;*�	��sm��(��̒�܏��H&f�����yHYrR���sJ�]�B�hX)�\"b	����5*�銥I������^����̪n�+1rq�Q�Z��5W�I����\r�I�y|���J	�۴J��%�޾ԪJ~|�z�����ϭ�{'��g���a�MB ��2R����ۼ�@A��w������C��\r�d2�(��y!�\\��[�����SV�Q�b6\rؔ�4c���&N(�\$��Y�r7&�B)���0lM�O/4��B�o8�7�@`uO�\00033`@xgBA�K�@��g(HEF��pu:�9�����>5�l���i\r͹B\n�������B`�6E�`��\r!�8)�z��TJ�S*�T�#��J�Y�������V��H����R�-m���2xk��_���/�Ił�e���!�Ă����D��uザjMJ���J���L)�\0��\n�T��T�uV�Cr0��Y+H\0�\$�W�\$6��U�t� �������I�5���<�%�N6`]!!N\"�X�J���i�(�y(��j\r>��`l\r��1��|�a!�3K����E�q\"%\"�vN�\$A�p\0�0�igP�!�����(�!ב��,6#y�q\n�H\n��ӗ���#^&���4P�Ț!�H�9�q�A�9������y�N����YYD,f�n��jmLc�]�),��C��O�U����ZR2�\\0��v�h�a�3�(v)Pcf�*��*跍d�)� ���4�[04�.�l[��*D\$B;�*Z���e�T��J�(;�\$湅Z�T��n*���>Q��-T�zFJ	�J��%yh(A\$���X#�k\$J�t�����<i�3# �&�p�ȸ1ąJkR�9��ڣ�N�PHP	�L*T��n����[�?��f-�1#e���Ib��Y;R[���)̒ڙ)�����uܛ�%�CL�<����4���HU��Y@@��f�@��P�*Xa\r�\$4���oe�a��#C��L]�o�d����nä�����p \n�@\"�A\0(�dK\$CUO�������\0R�@ �&\\ߜs�Z\"Nb	�t�DZ����Ť�e��>+[��ZWڲ�̸�73��X�І�%������k�\"6���8'�r2\$2��J�5�\$h:��ׅ�j#5�l���Ø�#�|�R���Z�ëX	�� ��P)sHj��Z�K��iwt�1iEuE?p<���1>���2�Ƒ(U�#���\$�2��\\�(��򰴑]S���R`&�eI�w#�zJ�CS������\\Jo�:�<�&�ڶc�������VPxiL���ش�B�e9ur�n�Ɛٯ���C.v�NHe�i�5�hǹ�c �`���A�:�H�x�I����\$��L�m�p�����,�5ɗ��\$�Ԛ&�V��m�wڛކ�aw�F�%*O7�h{�+A@�L��ꤢN���v�)hZs9�LE�zXMaP*��F�:03�pӸsO)�6Ʃ@��@��W.��a8y�'P@�n�����y�%\"2Ԍ�(	UR�?}�����U�%��B���یf7��U�����k�*���ڏ�?k�4/���n��\\@� ����/�x&F�O�A���ʢ]�4����~&�R�g��6M-\"���i���.����ï��O�I����O�ǃP >O�/�3�Yp^��~�+�a�BЀ� 	��Q�@F@�\$bJ�.�,)Nkj.d��F�O��ni����斠�v)\rH��o�~�D ��0�����\nv����_!.%�r�ߥ�έk�~,��C���,�C��\$?/�[0��J�O*�\0�ƄB�Vm��K��0��L�q��*�d?���'�.0��*���n��\"�o�oP����#Aqx?�M�pbdw�l��:*#7�k���c:��M*�K<��O��N@�F@���8l�l.�@֧%	�Dۑ�[\$�Q�&1S��/���z4�p��>�f�� d-�)l��N2��g�.\r��m�|�t&�G��Ͱҧ�%*�ǔv��C��M&D��2k2pm­&'�'�p��l.��5�}(��*���\\{l��\"�`>P\$��e�\0B2�q��1�+0��R~����pޯ6&�(����,�\\R�\$R����)o�,04<,������C%�E,n(���R��!0�\$�2�^Q����l\"�@�D��F��:.�,�B��>������\"q�2J�R�J*��s:*�Ve�!61 F��2�ZpF��r�P�LDl���ۇ`Z�!-[7B0��0�/3c��N�9g�f�x�Ʒ��m�-�p��xu�Hq���Q1CI�F�6֓���k4�?i3��(�������u��O8�S)1q�2�`k���\"s�B&��.�Q�ٴ��B�5DGL҃:{�L�V�HcXy�G5�����\$�:J���}Т���>�����-T1��*�-3�cC�yIRJg(f�Ji/����o�[5�P���u���Jw�X���h�M�M�����l\\����v�K&@�B���I�.�iP�C��2��\"N�AMK���C��\$@Hwt�8��u���I!%�\$�3Bf�H�\\&u��-u[VU^U)��\$�U�R��2�K�>1�-JB�D��n�ڒ�A��O`����������Zb��\"<�_[�a���.0�i3Ԫ���t\\u���=�oB��ҿ./�\0p��u�`u3.CL�/�`5r�`К��\r�V�5~�.��T�*�n@��`��ֲ��@��Z�+�Bb����Z���a-վ=�g_ϣ/�+fN]]m)5N���β����v]\0�\r����Y�>.�9)��cM�:�ޏ���=��_��b�!�/�s.>&:�j]6�{��1���\$����ϳ�,d�v�0_N�6�\r�F�2��\"֕p��{����4����1�AvnH�f�\r�q�q�	�d��+�q�~e��w��;pwO)�֍nj�����uSΕ2\$��5��0�B\0n�Emҕ]�CI�)8�D?�83��iyt���*@�O����Y�um�'ho����3�Z�6�t�q���j¡=�sr)A:��/B&���v.&E\r�4�64��ʠ�8���P�m~^cP�����ǧSj'�";
			break;
		case "sv":
			$f = "�B�C����R̧!�(J.����!�� 3�԰#I��eL�A�Dd0�����i6M��Q!��3�Β����:�3�y�bkB BS�\nhF�L���q�A������d3\rF�q��t7�ATSI�:a6�&�<��b2�&')�H�d���7#q��u�]D).hD��1ˤ��r4��6�\\�o0�\"򳄢?��ԍ���z�M\ng�g��f�u�Rh�<#���m���w\r�7B'[m�0�\n*JL[�N^4kM�hA��\n'���s5�dy�mE8Y����e*��	���(�8�Ю��\0000�R:\nX�0�ɒ.���h܎���6���z�(감4(�(9���v֧���A*�]\n\$�9�p@%#C�3����t�㼜\$Q*�(�8^����9��(^)��0�,&���px�!�h+!`ԁ�\0P�4�j�9�X�:������A�C\\��\"p�/\0���l�����4�AM#�X�7ů�<�\0UF6���&���KC<)\r �\rt�:)�o3&2<�\$x2�ӈ�è�?\r3K� ��;�������	���/C���0VB��<� �2�O(�];(�:'�\"��d(��O=��5��9�p��)�\"`Z5�0��7��X�;��BPiLh1A@R���ݾM��c�FB�Aķ�Bp�0�&,�\$�������l94�ÒQk���\" �X3��:<�:B;\ndd� N�8#Z��F3��Gx�-��[�K��'�c���kr��8�<-����V��2�(�0\r��(���x�3\r�\nh&A�L]d3�\0ڍ2���#2\$�52ac<[H9l�0#ss�SҔ\\\"7#ɔ��A-���7΍�ͣ]t=Kl*��T��)'\\ӱ�%�\r�,]�.��0�^�����ys���#�E`��J]e|)M���1��!Ȳ8�\$�rl�(��t���p/��Y����S)�6� ʖ�k�2D��`�h�0 }%�WU�S�&� :�`� �\r��&�`���� B�葒BJI�8;�Ȕ��rJ�aw�R�af�����<`�`>kH��*��I3�~\$e�1hZ�B���紀��� N����p�)4^�ܥ9�T�Q�8H�,�ֶ�<T\$��B����R\n����~���-�\0��Z��B�1\$�0�8�H\n\0�L\nF�ꃛ�'D�2`PSIIy�}|H8I��E��D5�@e�ɛ\r&\nO��v@�b'F�R��fzs�-�5޹��\$Id�y�R���UQ��U���f�a�*����E����\nȮ�O�\$��0��4�\r�(��\0��K�%&9��N*xЌ���^�c��'�4��H�e���m��9�p^Pp�t�S	�Om�ve0\n��}A�[�R����)�)A� dU�X��`s\r\$���\0� -F���S��Q�M#�/v�@�X���έ�����2%bF�d�(�	�0b^ �i%!*�sY�7����ʃ�B�Fl�1r�tI����T����B  \n�@(@�(R	!8#��B�xR\n�P �p�j�j�T�|0�`�o]Tp���+t�O9s����{��h��:D�d;�E^HF��\\���>w'�0���� ��PU�H&M~��w��oXב��FůB\n��@�X��3ܽ��BP[R��DE7��%���@�G�!eHm��T&rRZUR�\$�p�G�k�*�(�.vȮp	p<�x���M\r�.	�bA��1����&�aEUIēcr4\\TAȹ�:_TIe\$�nwE���d�!*\0�9Q�ܿB�\n����#��x�tL�#&cBƑs�A��Qu(;?��}_fOmH����W�aVD�<��w��i�\$��i�H��\$1���:M1]d#�n�Oj9~F�ۻn��jj{O�\n�Z�Q�5���rn���)^�I3��Oh�o�5�v\n{g�ϣ����a,�����\n�)�w��_�A04��{�	����M^{2�T\$��ɱ��5���\n�qW��5��\\S�yS�ビV�[~�,h�1�P��E�\nF^�w{��5��iH�^~�a�6JE�B��m9V'Q�t�q��{�4�\\ϓ�S������ߘ��O�m\r��hU�I�׾\\�Рd��\$�嫫��oO�]�0Eb���\\�/4�LW�����1\r�\0�x�0R���m���q���:�缰��E��E����D�� ����a��U�wl�|c<�\"@�\r��o'O�M�B���\nY���OE�=)����Ԭ�&²�[���s�|S���\$i�i^{���v�'�v�\r��ù,�)񰊤j6�)��tU��!u�5}Hv���6��GNa������i��,m��a�\\������	��h.e��#�k���L���(M������B����\$荆�&HP��Ϣ/�\0�0FB�\"��',��m�0FPH���,��l\ndZ[�\rE�\r\"�5aJ_�4\$�v��:#����2�0���\$�/V�ϝ��b�}e�Z��c.��:�@�e�Y���I �/�\0P��>��WBt�\0�����A�0�,���8�V��,�p;-�l��X��i�\0002e�\n��ż<G�?q @�q#4\"�D���C����\"�\$z�L|P��U\"��	2C�\n�ɘ[�����ؿ���0{mB���\r��3�_n�2b�'j@S�	#S�����nnxQZ�p�R1�1Aju��&��O`�`�q*�)�\"�Xg�~0\$5)�*��ڞcP�\0�\n�\r�ɞթ\n��ұ&x0NUmƭ�\"i�\"Ͷ}�x#�4X����N(�n�:�c�����R\\Ģr�	������ܥZ Dh@=��0j�'Bj/-ā�'p������E@�ax1�t�2��Sr���*-�	���\$�4�.�С*�7(/\"r=,����\n>�t����>���b����i���������cM|ib%�.2~0c�.�\\+tɢ�:�_*�|0k�+�xF\"���Th�4+��2�\\*e⒤r��1�\$��'��EU\0";
			break;
		case "ta":
			$f = "�W* �i��F�\\Hd_�����+�BQp�� 9���t\\U�����@�W��(<�\\��@1	|�@(:�\r��	�S.WA��ht�]�R&����\\�����I`�D�J�\$��:��TϠX��`�*���rj1k�,�Յz@%9���5|�Ud�ߠj䦸��C��f4����~�L��g�����p:E5�e&���@.�����qu����W[��\"�+@�m��\0��,-��һ[�׋&��a;D�x��r4��&�)��s<�!���:\r?����8\nRl�������[zR.�<���\n��8N\"��0���AN�*�Åq`��	�&�B��%0dB���Bʳ�(B�ֶnK��*���9Q�āB��4��:�����Nr\$��Ţ��)2��0�\n*��[�;��\0�9Cx�\0��O��2~)�#��6�nz�Z*�ʜ��Ӝ���S�U-��I\\����B�F�@�9��2/�\n�)IJ�6l\"�D,mE�ȌM%��YVA�C&E���\"�l�U�B/�N ��l�3� ���cx�(#��g�#���r@�6K��4�@;�/˹j<��;�C X���9�0z\r��8a�^��\\���t�MC8^2��x�u]����L\0|6�O�3MCk�4��px�!�\"4�\"�T��)�Ju6�)M��4��[��5�K�cq���`GU\\�'\r�wŐ�Q�jS��Q�wM6�ʚ�A���8����b�,�62��h�����7[IJ2FZ�\\ّ�N�����eK�QV)m�1�\".��3Ћr��)��gґ�mڢ\0T�8�z�#��g���:�����R	Nf����#�p�:drB��*�g�1)��3���Ϗ�47g�/���O��F*�|k��u?#�﵌����(D�EoE�h�+G��찀R��'Q�,P�Jy�wn�6�{	\0c!�\"��RK�7�d��u^�;O���s����-Ao�ƀ�߁�i��%7\n���m�5_�N�SW���֓k.GPѯ�Vؤ�X�-�(@��#U6���MCo�F3�]����p��h[��>na�x|�`��:���=�K	T(	&�i�g?]I����\"S�!hp�.Dg������U�G	DU4�h�U�@�왃�9�`������<F\0&���Q>�VP�}��h[I�|\n�t=	%C=q\\��:��OM�^U���XE!&;G����-���E�W\r]\"���A� ��U��QF �#�P�C,�5@)Շ v��v<A�3`ر��qM��6@��+\r��;��V��Ψ0Β��Kq礀AG���0S@e4�4-^��\"��EQ�n��HR�`�c1%x*��|�r��e\$���S:�l�l�E��N��^��}/���X魄������d,PV>�Y%M�\0[��ԅ�I4�3���w��\0��%��\"\"sAsS ��d=Hd�H� \n�_���B\n�M\\'ޡ0���jf�4�@�`j��^��|�����w`k����c��1l�6*��{(F��J��� ���+�C[Y��3Ы{Z�mD(��\n�@��H*V5����Y�\r/����+OA�Z��2�@�C⹁ʠ��C5�ZF��8E���?'���\0�<\0c���4��vg�|m�hLKip�@�td����2c�\0\n\n (��Kp����s>锤L�f����w�h��S�zOY���i �|��Z��7�|�/'�3��J4�n��ؾ�szo�U=L�h�-���ֽL����H�M�b-�;���.i��_x�PuR�x!�0��A�0�0�]:��f�Z�62�]!3`��ú�k^�j��ǈӁ�#�P-�2�\r-���l����4Wmё�V��yM�EO����D_x5�~AB��Xc�����e���f�*��Zi�[]u��S'Ri� �I'qd���yz�\r���]�v�4�ڍk5B͉�1�5�~rK=�vT������L�\0�¢��^n��4�U\$���fF<��v�-��	��8�3<�� ����6z���&P�gC.;<8�m��:76����&R�P�*�Ϲ�8�� =Kt#LK��i������;��`�@��u�qYg:��.���o	�v�� ��P�*^��� E	�¸�@�y~��p�^�f|͎�r5�����(��*U�y���,NŦp�/^2y[�v�=������Zi�����B�+��/y����\nE��	?�]�O��-!�B�d;�H�έ��=c4�9IF(����c'����6D__t���'�=]�y�T�H���x��jDv���\r ۨ��o���>/.X��m���/���\\Ƈ\nׄh�찐�ʐ��sD�Nx�~\n�����=� JXD%\\%0\\,�@���5n\\֊�snh t�#�M ��`@�lJ��hx)d8���Rx�}#\0l��9�DA��\0�^��`�R����P�����f��.\n`�Gb�N�gb\n`�=�~�g�*8�\$�����o������Bp��nkh��G.���.�pLR ��il(�g\":f���*���.��b`B����1�����u�j�n��Q*��B�o��Y\r��\n����8Q��	IM���N��jV�rEhx�`�q��f�\nJ��Dr+�,hR�B\$����:>�R,��\$���/��`.����/֝P�D)�-	\"��mo=h�Ì��� �	\0@��\r%��L�Z\0�ȣ�� {������\"&�qCz��#f���X�/�apF��1A1.���ʎ^�����DCM^GF����p��D�%�prlrm��.����	)`\\,�F�B��S i3�q(�����͗)�j��)����PlHW(�y)&�Q\" ��\$r����␘ѯ0\rgR�����R�l>;2_+g�2���,\r�,���#,��*�&��\$\$�/�����p,F�3P�5M`�r��4�](�28���E��熒Mr惪������'�(��Ӂ5�]��8��8�NB�(S�(�)MY*��K\r%R��6O�)�h��m�%l&4`�ONv�3.\rq9�[7��.�f�m4*qb�e6�Q�,�z)��3T�s�eF�A�4�o<��&����Q~�	-��e���D06bZ\0�=��P�Pp��-Jeㆉ�\0��0��`�ORI����9���m�~�%5�,��b�s�C��HҬ唇F�I�Ώ �N�eQ}I��B�<�q5�KCM4�����/3����ԱѶ�TFiӽ/�\rMt�\rSGM��H�V�OJ����M��*�Αh�����Ǭ =p7�؋��!��N�Run��s��2r� �M ��B5@�A��J#R�Z�rsU�V,� ��%sgQ�����ދ�[�k-�)4YN�j��A��HU	X�c*\r4���u�\0�+��R�	��F\\v��D�\rWUm�Z�2�u��o�O��I�����+�������ڭ�iNM�*�1OJ2�^X�\\��lmȕO�;PN��_�u_�Y��Y�]a\r�P�z+��3�T����]Bs�`��P�1R�\",�-Ra-�J�VY�:��MGv7dr�m��ӱ�^�L��]^�0�f)��\"q=��\nuCЂHk=��%2��.�&�S2nU�b��`��:��m6=VAK�0���UV�!�btzմ�b�����LTI�q�cX�q/��Z���If����d�ni�Z,�adI	����g|�`@�r�H#�~h��A��6��rP/����W�Gq��D�gs�sQq1c�L����q�t�w#W�V5g4�V��SO5� ��d�W�Wj*'Љ�Faw��n1nu��)U��`�����?��H/.6��ׄ�ױx��1z�t�@qiv�k�{V�-��lu���yy�	`�dx1c�~�#!V)K�+LXE���0�ԧO��t��b�E< b~��\r���T�e�7t8-n��xsgxv�8|��ON(um�C�����7�\r���7}Xi��!n��a#���#�e:w\n渟��Y����8�8݋�e�쭅4hHQ�O��;tr'�RP��/�mh��tJ-��T�|�\"���Q������iV�e;��}vaN��L=h�i2�gӈ�AfX���e�*ߏ�È�-tXDw�|���l�2�&ykW�Y|�fC��!!i'�r�)ٌ����%���,�';��%~�m;o��xŔ��p��TȐ��&Y��Y�d6ߕ��ы��XQ��a;�˅���+\r42e�0C~/�Z��!x��W���q��a���R\"'�F��j�>�mxU�ɵ�P8;�q�p�O>y�.xW�Z\\��� zIek���z��Y���\\+��Mv����E��F��}0IX�'�O1��7���ٷu�A�O������@�l�1,��B�!S�.o�5ZE��6ȱ/��E&B�&-E��pg�(:�rZ�5o�8ҝ�������Ύ�_k�M+߲�o���<Zog�U������i�\r�V��`֕��[�Zu�� ���̡��+��� ��E���<\n���Z	^�n~I\"���ǝ,f���W�1nǋڽ�9�P{C���egѻ1:�kt��NwC{ {��3ch-~�P<��S*)�ҰF��5V�U����ӷ���k���M,J\"�&�#}�W��G#8y���A\\3�q��KȊ�7�w�\"Į?m�;��	���F2[���3�[ZC�T>�6�����K���=��r�7뭮^�h C���w��(�Gz�R:���PƻL�����ۗ�fkZZ�U�����+��m�m�ˤؑk�ٿVՃ�u��݄@��C�;��Ϡ�a�Z5uu	��ˮQ�1y/�����Q63O,A���������� 	�Q�?A�zI%�zQ��ZS����.������\0�^�cj�)X�O\0���\r����c�d�'�����mƆ�8x @rG��8��*0���v[�LU�5yqOj'�\\�>��w��I@I�e�k/���P����>}�����/�JP\\P`\r���>��-cз�n�b��=w�u�}LD��	\0t	��@�\n`";
			break;
		case "th":
			$f = "�\\! �M��@�0tD\0�� \nX:&\0��*�\n8�\0�	E�30�/\0ZB�(^\0�A�K�2\0���&��b�8�KG�n����	I�?J\\�)��b�.��)�\\�S��\"��s\0C�WJ��_6\\+eV�6r�Jé5k���]�8��@%9��9��4��fv2� #!��j6�5��:�i\\�(�zʳy�W e�j�\0MLrS��{q\0�ק�|\\Iq	�n�[�R�|��馛��7;Z��4	=j����.����Y7�D�	�� 7����i6L�S�������0��x�4\r/��0�O�ڶ�p��\0@�-�p�BP�,�JQpXD1���jCb�2�α;�󤅗\$3��\$\r�6��мJ���+��.�6��Q󄟨1���`P���#pά����P.�JV�!��\0�0@P�7\ro��7(�9\r��Đ�����Z�Ի�b8��+�q1�a8�0�¿�/\nzL�)�5''��Q�� � Si'qyJ�S�{J���7(��\\1圔���m<���W;CN�*��� ��l�7 ��>x�p�8���1����3�\r�A�����L�گ��9����4C(��C@�:�t��6-�9N#8^2��x�uݣ���L@|6�/|�3N#l4��px�!���,,X���y\"mӷJ��!r��i��J����R\n4`\\;.���8����/�iL��Ǝޣ2<R[O�e=#\$Vr=��p+�#��m�iȓ9P�]@ 	�Y,É�hFP+�R�+4�v��3�qI�%Ɓ\".	ܳY-�sm���<Y6\nں��	@\"^��6Y��6�.�����.B�1Gq��\\i����*�ث��\\�.�3��:D>���%Ǝ���|9Vũ�a%QZ\0Q+5���󺞧z:{��qc���R�|�浗����ZB7F6?�c�a\rψ\\9*QH \naD&5PR��+������Z��jȈ𩳄WYj�Q�%�0��ѫhb\0��Ǌ�<TŢ���.cĘ�2��z�{H�QC��\0����	?%l��3�;�#3byQ�Z8F�0�\"���ވj���@����s�%���m\rѴß���(x@��m2��\ni����(�zHH�AH\$�x;�e�3��߹�� 5*��Z�8D̃4��� rG��6���0f\r���V���Whh܅@�|��n �:�����ft`�6�ΕC����a��R� � ���`�P((`�Z��p��<�W�JK<֎�,p�m r�bh?�p9LE���ěɹx8�C\"�^G�z�u����`,\r��6�S�*9�F@�A>��}+26JV�٣��/9X�\\6\"�K�b.%),p̵S��T@��/�x�\"ǚv�ɷQ�`&�\$?Xxs]��6���W�d��y�����`;�F�p.a,-�ǘ�i	!�8����&��Wu����}!��,����\rx����f��ʪ�Lj8�@�T�g�.[���2@�Cc�G�Ç)�]�xa�=hLu�T�S���=6.T��s5�:�^K���P�#�����i����)L�Lc�-�����;.s'o�\0�\0(1\0�!HOu���%��%�QU*�(!��g��>G����[z�A������mo��^����I���>Z,�ncG�o6�&�l@�h7	����m��C��C:����W'n��B��K\\  aL)f�uK�IX[C˪`c��B.�Ut�D���=�\0�\n�׽��)R��n�X,-�,�Wf�\n�l.��eS�sN������S����;��^:���IC��Y4#�`A�^��ԇ5���m�5�db4��j����c2�C���'�h��{�sE�ȶB�i�6y�����U*�\0��!ĳ�Qh.d�`��c��u|א��c�Z�[��֞}\0Sb��n�<a�` ��u�����M�4�E�6�������'(	;�;�&j�������8.�\\!E2�p \n�@\"�@W\"������G���y=��=Z^��sN\n~����]��Y�S��C�\\�F���B�Ӹ��kA+.�B���kXCJC�u���a�K�0A��w�\"09G[aB<+�2�\0��Kj����*YrsѮ㦆ʒWk����2��Zظ�]~w�o�\$̖�;^KT����IƖ\r��ԓb\n#\$'�>2���.gV�\"���� �׀�EV����&y��zҎ�3mC��gē�wL!^K�3�CӪ���p:�R~l�\0�ij����,�Ko>�;��M�Z��-��\"�L'cW��Q�s��P��%���p	�'~���ò�������qm�e�f�� *|਄�²B�~o���O����&��>��?iTDF�<'~vo0;���DM�1�Z}hԝ\$^(Ì��nE�V+��q\nn��\n�l��F����� f��Kރ���� �	\0@҄�\r%���Z ��D\n�Hڇj��\"1����yI�\0^3�D����Wbvi��	\$�&pā�8��wx;m��̃�~)�~������\r�L�-P��Z��j�e��	&+��O���(U�o��0�	��E\0	��\\�@N\0�m��C�h+e&�#�2���}�n����1�p΅K �\$�	��0D5�|p��p�lF�\n��E�߅��g��ȑ&��1�D�&*3���x� ���9�v�B�p�l��~G#��-\\6b\0�3��9����N�^�!hhf���D�,9B�B��k\nZ|)��\r����b�H\$Ǧ�B~-��<��V�d;�\$2b<hy��c�h�ez��&rQ\"�#\$P�/�fҁ(�����82����쒌��R�o�'��1r/�H����e1�|F�s���� Ȭvd(o�C�h+fj{�R����b\0PQ��G�/�r�0�\$|E1\0�.�i(8zSBF�)�32��H|���.o�JI�N*+��\\���S+j��3,Ї�K\$�e*���(.s\r+�/6�w�M`���ʦ���7���S\0hT�9��8�99	C2s\\9��3G�y��'��i0/�h?\"_f�;p�7��� Id.��PD�U�HIN�7s;@��:T��;05ƫBҝ\$�@Non&ÍBf�\$���#Q;�N8���&����H�s��,sLDG8{'fɓB�3<��;��j�p{�w�<P�H\"�3IFPT���E�s�c�o^oo���ly�=�׎	@����L4j�Ƕ�t��K)\"���4i�G&�o4x���i�*h\$D�hSP�WA��;�?N�7�Eu\0;�LlT�R.��14��rp�xx�� ј��E\$ӧ3�<�\"�T4�A\$M:�:�3Q�SwUQ�[R��R�T�Ӭh����S�V�E�NkuRk5��U�<\$�e�|��:#h+�̂�X�jh��g\r9C�ȗ[5rR���`�]�'uI7Um]��]��5PӱV�W�^�<��ң9tSC�7�)XU���@@ܿC2U�Vv>��[\0��ꊰ�<���\$�vE�N5c�U%�9���pۂ�F	4�뀩�A`ssAumg,agu�L3}a�-2�gE�hp�*URV�ah3a�8���cҥj֕1�DG�Y�W5�T�9	R3<��P��jB�+\0��	%Sm3	��V�J�6�Q6��gm��	�\ro=3���\nn�bz���P��\\E�k>Ĕ�oW2HB� ���_�� �6�fm�yG8�r�[��s�+1�a<rG^3�9��?�[oI\n���`�m@\r �\rm�1S8��#R�ۧ�݀���Ɖ�\n���Z���[q=��?5\\;b��WD�mY5����&�N})��5f�@	��{��(�^R� 9��.gy�E�n�hK4��UN�S��9�Ҩ�XrV�n�fN�2b�+\0�\r��c�[�xI{�p'�gη-,���nw���C�\"t�.���u�a\nh	�dD�����i��l}s�HLg!*��;x?6ԋwإ��K^6}_�����=���`�a��\n<Xϲ�h�(��F�P3!]��D�+��v�v�?Ks�����7�5�QI({13���5��|�q��DI��h����FKn|f�\nŬ��\r�\rON���R��B����^'�=Rg�#�N�����̇ǩ,�l��OC��E_1�r3�p��5�8����N�F/=�έ1\"J��\r����@�y��&+�kU�轫��vgB�r\0�	\0t	��@�\n`";
			break;
		case "tr":
			$f = "E6�M�	�i=�BQp�� 9������ 3����!��i6`'�y�\\\nb,P!�= 2�̑H���o<�N�X�bn���)̅'��b��)��:GX���@\nFC1��l7ASv*|%4��F`(�a1\r�	!���^�2Q�|%�O3���v��K��s��fSd��kXjya��t5��XlF�:�ډi��x���\\�F�a6�3���]7��F	�Ӻ��AE=�� 4�\\�K�K:�L&�QT�k7��8��K')�NgI,�n:��]�gn|c��7�+%��1>ň#�(��Ħ.8�0��� ��܏*#x�9�\n9��������Ɏh0�3���.���H�4\r�.8FC��`@\"�@�2���D4���9�Ax^;ʁp�\n�H�\\���zbǑL~9�xD���jJ� C2J6�K��|��� 2�`P�0�	�X��֏@���ȯj��*cJ�:A+s�'���IҢ�\rl��b��a(ț0C�UU��R%�듸*/�����h'��|�J3��.���uN���)υ8#8#Z�6O�UF���c P����#�떠��(�=^.��4�-H�ϥ�0�����R����lc�8oȦ(�����P�>�-;w<��<\n�P��\$OҎ��O\$Vu���VO����I��T�d˔�2R��TBR��\"ׅ\"I���(z6��Y�ٙ䙨�OY��\"@Tg>��S�(����\r��\$�[t<=�r��jB3�NeTR���ք��{=ObB�-�4��-�ϣ�P�::f���hɥ��{�I?,�\r|��0�\0�0�O�b��U�Z��\r�.� ˥\0�\r��bU�X��\r5��\"�`�����.<�I=���P&�S=p�ྨ�|��05���|���2ͥ�fv5�u�t����H�D�&I҄���VK\\�%ļ�y3m*P�`|�@�,��8'k��eQ��7'D�����j�\r�rf�C�B\r��:bbS�#%��G��K:*��#�b��Gw0�2P\\l>�\rZ��	c�)%%�Ԟ�R�UJ��\$���+p&-�&p��)L&q�A�|�+ \rЁ��:�ԉ�4�&��|�3`d�!&rhCk�3�6r`G�ɮz�}��E�|!7,\\S��|�փ�y`�4C���!2m�p��r�4�I\r��B��P	@�Ku(���\0�����Rz)�8�2 ��ʦq�X���ݖG�P*��E��݂�\\�ưH	�q�9cG\"\$Q6\$:`p��lC�\\9��&fH7M��8�\0 �1���a65�M0��8GZ�zwL0�F���Ϝ��r\r�,2���a��'g��v��c�^�Ag3�ZYd��2B��T���r�\r�I<p��<���<�#\$m�F����1�D�f��W�\n<Y�ӈwR��y/`(�\ri �\ns�Z���wED��SK�#��p�C9�jG��Ce���MP�R^F�%ɵq�sɳ��\"L�U>�B������ >=�5�k�#\n.Bv����-m)0�7�B��Re8+��P�*Y�rHyG�@@*��d������\nnu��0\"�[�vC5ۉ8�q�5�V�����e\nc���W����]B�\$阢9(N	×�(,�[�P�e�A��`p���b�����Q� O��M�A����mg�6����@��3G���\"-C�ih�K(k/j \"GR�\"���6���eԼ����ra���39\\,�U;��αp�ː�nS\$����+U���ɩ�48����A5��Bp �*\r��-��nQ�Y�09KC����umLW*\r�ym8\"��͑�-�r������\n���i���J'�BN��+k��\n(+�v�\r�|	��K�g\"v� ׍I�;6�Aa �E�%M��9!�t����/����^UՃ0����A}^�U�]A:�75.]��\$�+�������0�}�dw>��rQ�o�O�3�w��pcY�x\\{��ʶ񅐩���3���R�IՃ��RNJ�\r���(�[Ӄ����8���s���y�:g3��5���aI&�oK�>�E������ⲹ�{���;y�;�P�Dy��d�#&��`�C���a�+5Y�E��;?�y���C�4�� .�Y(�7\r����@���I��\n2pə^��	�l���f��1��κ~�ʞI���Uv0��5�~SR�YK1g=գR�� md+`i�F�^S/��(�5��ɓw��?�����C�THJt�x@J��jpfuX:�0�z��f���^I�Z�?�G����J��@���d;(��lP�b���[\0D�%tHp\r�*nt�`��PnB�J-�E�s�?!b�:��&J��&;m\\aOP�/@�k�խ^����q+��b�/h�-Fp	���\$V�PE�D(iΆȃ�:jD��D�	\$�	g�?��9�&ò=�\n	\$�>Ǩ�Т�b�0���B�Րd���t.F\\e���RR���\rbQ- Ѱx��@��\"Q�k�Ї\rp��p�͖0Q�21�~ّ/H�&U�[�F����Ã��pN�-���#p\"�Xv(�@�G�a�O��&Y�AQW\0�J[�\"��>O�4�\"D�ш��[C��\"WQ�=�s�O@�b��`�\nmlX�6�Mo���o��E(�1��-g��Qn;�:>m��o��/0�2	� ��h�jl}��?�{��D��@O22�k��e�ݣ2�cr�R,��w�x5�F2Ǐ\$�4�O�,?łϰ*UM�[�0U%V���n>�pZ�o@؇��\$#.}c8 c`%�.\0�r��e�/圭���\0��Z\$��l\nɨ�a'�&����)+\$�(�-��8��j.ln&\n�\"R�h&ql������mQ)������e0cfU���G<}\"�|�+�\\\"�Vu�/\n+V91���on�0GVk�^W,�@�O��	�Ȓ�%2�	�2�17j^�h^�e6�_�_8�` k�\"A�B���!m(.P�7��Q��P���	�2\$��OL\n6O��*�:�S�jo�1 ނf8�.o��|�lW��R�5��5',#@��Ô�.����@3�ig&10�xТ�(s7��-�4V��?��k\0�#��R����qs���^-Ч�";
			break;
		case "uk":
			$f = "�I4�ɠ�h-`��&�K�BQp�� 9��	�r�h-��-}[��Z����H`R������db��rb�h�d��Z����G��H�����\r�Ms6@Se+ȃE6�J�Td�Jsh\$g�\$�G��f�j>���C��f4����j��SdR�B�\rh��SE�6\rV�G!TI��V�����{Z�L����ʔi%Q�B���vUXh���Zk���7*�M)4�/�55�CB�h�ഹ�	 �� �HT6\\��h�t�vc��l�V����Y�j��׶��ԮpNUf@�;I�f��\r:b�ib�ﾦ����j� �i�%l��h%.�\n���{��;�y�\$�CC�I�,�#D�Ė\r�5���X?�j�в���P�p�`Ͷ�Jb��D�b��d*5\"=�[ލL�����Z\r���>ɿΩ�2\\�J��hq��\\��V^��0�.��.��P�2\r�H�2�K��9Ţ^媊y�J:�D�����%rc���d-6���k2��xX�@4C(��C@�:�t��|4%\rD�x�3��(���9��0��K8}��1h�[��'B��/��|�\$��i\r͈�Ħ�0�'6\n�V�T����M�#e���i�jLX��tWr�4k\0��Bb�K��@�J�R��D�`J2Tk^��L�e�F%�_��e,)�#�hH(�D����@�;���K#D�>�hw�f�.8��l����70j0j�65^ӻ,���|L�E\n�ܬƯ�4R5hj�s�L#l���D_h����`Zݡ2��G�2�hæ��͈~-4\$I�&��\0�J���!J.�8���!z��z��n����&�B��&k�:fA#Nls�9m�S!Г��8'~��p�O��vo���.B��B� ������)��F�N^�+(�T���(������m�i�%^#�qo\0h=�`�^*�6'��1W���z�!<ڻ�pD\r!�0� �K0s,�;�#Q%���貃�i\r�9�Sr����ЋPӑ�2&L@��8���N~d�ʝ��4�i�A~�v��rm'\\����CaV6�IP9BhIދy�����TzSs�)ɔX�W��x. ������;C�4��6B�3(|�]�`\\^de7q��F�(p�q1�\n����AP�܏=@X��Zt�+�A�9�+-�d����\$JLfi�U�F�A�z��RyGiE\$�\${��~T����)����E=�-E�aB� ������%�.��.Y�7E\0��Q|�ƈ���5�u&r\n��\n\rB�p����GrE̵X����V��\\+�x����XS�b�u���\$3!�,�D��1q.��].�ԐϺ:ͅ�0H�T�\"5B�2�G�\nA)�H�Ɯ�t�����GL��T��K,�����V/JY]<�\r:\$c�W��g4��A�z�Vj�[��v�U����c,���at0��Qk\rLB�)��5#�`|A\$g7p�xO)>JL�|q|�7���R�'I@�R��T��s�YM�&��K�9�<KN��x����2&�cX|ʍ�9EС*�lT�][�z�Lb���H�����N2�9� �:�H��[#\n�Բ�T��s9b^��6h���|��\n (۱^��1��pSaf���qT��!���UJ(� �k�5�[�4��@\n1-�#�cRmTT��K\r�gh�_]RP̴(���!ICo��s!�R\\�ع��JL��H��\$�^w\"K:T4}6fI���>.�P��0��1植^R���]'	]�E�-T�m<��4C���_��Pŀ'����rA\r�f�����.ﰕ�:��R�WM��:��-����<!-b��Xi�p��(T�M!7�B��)%qAN9�����(6U-�F#�#L_O:v��.��!R��;�,S�N��(�<���5}PtL~�%P�;hѡ��p\"���qb�^:�l�/�Je�N;t�����&�=d�:�%�l]\n�R7Z�x�X�{�w���P���.o��W�p�Ս�ʕ>��d�5r%�c`R��ה������������G2�4܌�r'<4P��ה�ԗ�J/9F��ee��*�b�7�r�@IZ�1)�����K}������A�r�|�I���5!\$�ݴ��&ۺ�\n���M]%BZ�J�z��ٸ�-nb=�^�N�}.�����6^r��d4�Qf�]�q?�ԔE姿�b��\n��w��m��a��,�m/d��_��cAH7���V�u\r~�����\$����җvD.�ôg���-*s�����\r�y7ĥ\0�2��ʩ7}���v�;�s�܆��KQ|��_;�@\r�a��Dl�\nm\n(��`����8O�u�t��l��*2&|R�J��s�O��ܪ�c��K��Ihvg:8\$�ŬL^&�-��\\�>�d�*���4���X����Z��\$�z	�Kk�5�\$���\n�� �	�G|��:�E����,G�>H\$*w�8^FZ���E�aE�f#q�s��\r,A�6w��)\"N�\"�⃲�g����0�q\nH���	�_\"�p��fj��cJ�k�OO���ВӑgeA�q�#���9�և,�w�~��4`��'�Z��1�l�8�P�#�p�ӎ�\$�_koQ>ð��Vf�2�0��sl:챆6�0�6���7�ڗ\r�%��|m��w\r�\"b0]Q�,qX���l>��J�m��-�v�'FJ�f(Q���2*D�,1�>�Vc��H���L�G��GX^�\0��&f��2'�4j�\\KNF*^����&D@��D�Ȏ.�gN�,8���K�^�p����&�(X�#B�,b#�\n��/�b�o(DRҲfgҐ4D���cǰD��*���Q*�n����T����\"����%�*\r�Nf�2lF�w�<����>m�:���D,�\$�e��z#CL��&['���C+��%3,��k�Po��q�B���2�*ǝ)��*e2{�tǼ�Bd(�@Qt)r.m,�`IE:��(``�e��Istlģ3�|�|sOms�7/8��7��,�4N�zS��ӣ+͟'2���\0��5r��h��1\r.�hUq|cd�8ϋ43���A3ʵ���i�,�*�=�e�x8S�4���<��?S�?�a=k�=��I���;�E8�P�p2�.23���'t;�����IC4'QyE*CC�Y�3�|SEd�lL0�k8Gp,)��(F�c+�S/T)�<L��8��~b��HIH�~Cp`����q�J���\0thn7�2͊r��L�KlH+����>n#EQF�E����\$^�(�F�{�}P����#�5�FL����>S�4O�R�F})�9R�S�Gk�Fh\n|\n�BKS�����=D�AE�1r�S��A1�V�mVUJ���;��HU~���X�PP5kD�*�ZhBd��>~+ha'{��2��c��?��=��D����E\\�!T\0�^�7V��=U��TuXS�T&{&��x��b��yՎ�5�Br�YUV_	`f�V6|V\\�\$_�\0g�H�E䪤T�s�E\n�]O�V�Qd�-�WU3T6YU�OFM,U{`�b�eϏU/	b�-dĮv�F=W�K6���vP��i��q��*tCR�T6��.�?�_�yT!J>����~61_1�Q�\$���+�\0G�>�[�\0[�Gt\"�)ooS{	��oџp�-'pv�o�8p��m�.φpgK]N�[r�1�1ѽo�p\$�<v�kS�r�>�q�SF1!B�K�v�^��u�UB�~@�� �p��H�l;lQJ���ua6�7B-b���4�j���4Z�Q�Ā�\n���qIs`Tu9,(_��4P���9�<7�Ps�~�MCK}��5Qq�&Ij|.�mp\nv����\r;�J:AdO#t%(rD ED\"ǖ��!�␊9,��@v���e`-.��,�2y��L�İOxi3.��@ՓnR�\"��P�����j�<%���H��1��e*8�8�Y,D�X��ԱSZ�W�|5{��ы�8Ui]�I(��ҹc�>p�\0�h������?o�Tb�yaP_x�,L�R�PǬ��%-�r�\r��t�~hA��o�L\$� �\\���&*-/1&�&n\n��`����X澯�%.�iP��+s��LixP{���D4�=\$j�\$�qT�.����:.��s�M���RH#X�V8<1�u����+N,&/�N�I���S���#:-#\n��^ReL�CH";
			break;
		case "vi":
			$f = "Bp��&������ *�(J.��0Q,��Z���)v��@Tf�\n�pj�p�*�V���C`�]��rY<�#\$b\$L2��@%9���I�����Γ���4˅����d3\rF�q��t9N1�Q�E3ڡ�h�j[�J;���o��\n�(�Ub��da���I¾Ri��D�\0\0�A)�X�8@q:�g!�C�_#y�̸�6:����ڋ�.���K;�.���}F��ͼS0��6�������\\��v����N5��n5���x!��r7��Ċl�Զ	���;����l��# \\�	Z:\nzT�\"�P�i�>����2���A��QtV�\0P��<���0�P6��(��� �4�#p ��k��=cx�9�c|(9������1�c����c �:#�9��\0�4��x�*���9�����4C(��C@�:�t��2,��?#8^2��|�9��^)���̮�������7���^0��p�2�oc,6F;r\$V( ƀ����a�Hk�(jx��ed�_����3��C+�#��-��(ȼ�#��aH!�#�t7�%�o���h�&L4h�'�dH�+`�=#��\n��:��UV�n�v�'Jv7]�2pJ���G��+�5�%����n]�7��Q7,tW�ë��Z���^�i\$T����2H;F�R�!	\n(ܙ�7��(��S���d΄�[�46)�8@)�\"`<U�P��Y���d�H!�b&���W��i�X©�މ�U\r�\\WC�x����5E�X�MJ<1TY\n�P�:��P���1�p�w��L܈�ǲ��H�0��*�?!!�v�gsی�Ŵ7KU �cG8]��=~H�/A��:�\nf9��@\0�a\0�0�\"�j�2�3PAḥx�3Vpʥ��Cq4@xũ\$�:RHS�1|4�54vt�ـnN�\\Q�4xr�J�o䣭p�P�n2���\"Q��SI�B��Rw�CZLS��T�]�\"F~��:7�C\"HN��<'�����P�;���r�Q�D2*ED��>���Q�L�	pP\r�A@t��M'e�C� �'mbEw��,J�X%�5	!D,.��\"|(�_�FnKS�wO)�>����*�Q0��E䣔��xJA�0��=DP�(�b�>}��f>\$_@�h�\"��TH�\"ieQ�՚�R�t���4���^S(p>��6�U�C2چ�A+��̾�HoG�3��h�]�� �� �鄿�9�4�܄A�j�8 --T�A��vB�\n\0�	�3DxK�p�I�)!R?`�0��to��9���C<�M�Q&;\$��f1��.9�k`�>�u&0曒� ;s~�����joN!�k�x�)�0gO�\"�5�#�mc�A�2�PۀR�4�+��+�34�Ы�ጡH��ݺ�LĎGS�bAp ���d.�z��7��+���%��\"Dȸ�8��OuW*e_ 5����D�����˰ܵ��O&�%�@�~�9Qs}[�ޙ(\nLM�d�DB��!/d���^xS\n�,���s\"-We�틮�fGk�:'����nNäu�J�\",V�\n#]�K����\\�U�x�䀫˃�Om�A��#J\0s��+%=��D8r]E�V5���ZMqH��\\ŵe�(Nb�%jr�>�\0A�%�M����N��z���a�Ya�F�#t���E�\$���	|\\�]ԏ�@���Y�Es��2�BeTyd���؋�����[�[�s\\��\$=��D��1�Y�,g���.-&��|�&tW�^Z�?��xrw:������Ȅqg�(\n)Ӟ���W�(�);tK0%��itHk�t�Z����K�� +\0�F��e�-a�2.��kCY�\$���N9�X]�i�O�*��H\\�tr�y���43���~\\�7N懴���&�7��`�%��	{t�-�N7\n ̀()&� ����d�![dcn[oǖ��ᆄ`A\n�P �0��\"K}��Υ��)Y!\rq�@KiZ���qk\0^rL2IfWC�.ŉ;�;�)���[ə5&4��A8�##YT��3^��|���EjIw ̓�5��B�\"�H��b���*V�i#!C�ȣ}��6<c�h�]n\$P�L7��R�a����I�\\e|b��S;�9�}ﾑ�\r��`�\n��S��x�7N�ލx���	��S�[�0��(e]��2��#n0�bЊ��Xt�������M�Oj�K�^|��um���k,�Pk���n�g%���8D�斃*��\"��G[ϸֽ2������~�#Vь�b[?H� �T�.�+���ꈘ)v����a�|������o�t�6͜א8�\\:�F�j�z\r%�������l�;�f|/�����B�l��0N�(�n�nB\\�<\\�La�M�H����m�:��H%�,C��:�>�c��O�/�9_G��\r�	��\0��f���&<�\"'k�c���b&m���*���N�Ұ��c�=�rZÐ�-,�R�d&�@!*���\$���b�	p���p���p&���Zm���#���\"�Z�˽	�HHՅ�X�u	{�]0�\n�@)l�s>h����GL�Ekq-��^0�k�W��ь��]���m�t{���!^B�l�r�c�Ge�!���,\rm�:%zN��rEv�u	0pMd���|?�~ю�q���_�|�cn����\$�rs�� �o�(�g��ϴ�l����9�W�y\r,|\\�N�#M�	NL*�\$T C5���N<%h%��G��H�ζ��xANfA�L�;%Ry&����\$��(g��Ҁ{��\n��h��А΍���ep%Pq�X0�0�r�HN:�C�̋�i�|#��Ҍ;��\$Z)r�!�<EJ���b\\	�T�bИ��c���\$~��\$�r=�,�p�/O�4��B�\n���ZN�\n�&�C3\n�ֽl.U�@�����*�R<{�6exI�Tm��/q�Gϲ�ϸ6�U5Ok5e_5�JH#f\\�Z��F�2j&�ı�������U*i��w�;b7�Fvr�i���l�qR��W<�E\"|\$�p��r���\\bъ8F_5�r-�\$��4C�l�7��=\r+*���k��[BiMB�h`����!.s�@���-t�tY����(Arb���\"�������C\n�Mx�\n��'��g�q�P\$���,9U#F��wH�('�3ֱ@\r��>�������8{�\0003�O'�6 �ɍ�Ӕx�j�I�\0�";
			break;
		case "zh":
			$f = "�A*�s�\\�r����|%��:�\$\nr.���2�r/d�Ȼ[8� S�8�r�!T�\\�s���I4�b�r��ЀJs!J���:�2�r�ST⢔\n���h5\r��S�R�9Q��*�-Y(eȗB��+��΅�FZ�I9P�Yj^F�X9���P������2�s&֒E��~�����yc�~���#}K�r�s���k��|�i�-r�̀�)c(��C�ݦ#*�J!A�R�\n�k�P��/W�t��Z�U9��WJQ3�W���5��.�\"�.T�{��D-�(�J�s�\nZ�1H)tI���vr����s�	�A�p�2\r�H�2�GIvL&�\"�s�| �����K�̂�N'+�\0BI��1g,����\r��3��:����x�'��1\rC�p�9�x�7��9�c��2��:e1�A��AN���I��|GI\0D��YS�,ZZL�9H]6\$��O�\\ZJ3qr�e�R+�ZK)v]P+�V��)\"E!� @���A��A.��0Y<��řQ9UAU�QPr�D��G�0�Br���=ϥJ�C���M�d�����ZH�v]��\"�^��9zW%�s]Y��x:DaJ����5	CL�!X��M�r���B�\r�D�m��)�\"eL�n�I����54�!P�0>D\\��C�^Y�7OTV;dd�5SGAM2l�.����r��F]�4p�iP,uOS����o��9�1K!%~���:ޯ��%I�X���Xs��22YiUc\nR����K��]�Xմ��]��^D`�!A���}\\#`�9%�	�S=�����T)��0\\��'Ai�����5:e��UV\$1�I-��9#e~ҼkXO�p^�Ɛ���XXC�����69u�ynt�L�*&��ed} HR\$�\$IRd�(J^t�+�2�^2\r�p�:\r?��1L�X_!�A�\"ŉ<\"�Z��:(�`���;��x&������7W�-���x(T������pTN	���\nCH�\$����úQJo=+%���(x�l9�T����hzL=�6���\$e�J�f�+���r�\n9D`�mB�B�4\$��\$0@W��H������s	1��AReUެ>��b����r! �@�mϻf)˨�1�EDF΃�q�D��?�\0��d��\$G�P�J<	��h�\"�pg���X��\"YA��W�Q(�i�F�\"@�S@K�0q��r˙�4%�P\nx����qj��j+����W�D&&!kR��\\�qS\nAqI+�Q+u�eNKa\"���v�5ݒ��-�h�_d��JRlN	Ҭ¸Z��R�j��P5v��X�1��H�N#�jTS�|:��,D0B��(�\0�,*�@'�0��Q��\0R\nz �� �(\"E���� \"�Ţ��t�UҺؼ��N�s�  ��P(�B(	��V�P&�|k���X'���&�`�P�Ǖva@|�x�A<'\0� A\n���ЈB`E�e�V�z-O�i�	@Y�%E�:�<D�:�p�,�7����p\"�����.ԖQN.΁���է��&�Nx����ڗ���m�&h�~ࠇ9�@�o�&[	��YDQ�	�J��`��\"�FNӴ}�P�͡���*}�з�%�s8Kk�2�W2���M����&�r�m�:2��E�\$⎐�RjT�)��(��4�<\\��\"KEА,�m���h�Ab*�W*�U(�'��\\1t��N+>��M��&�H�Ҩ�L�Z+�Ae�0H\\�����%\r5Ab��~m0����@��@�<gLQܶ+��E<�eT�\0^3,�%�|QHyA�`�wH����#ĺ#�7H�^�D���Q1\"�%�լ���/�uw��D�����F!}ڼH��(��B\n�&�����_\n��|�`\n\n�1j��c�)M��xHZ-s���cn��r�4+W	'��B�|�zH�Rb=�aqm�y/e��+��m��:z=��Lќܘr�+FpwX�(���G�p�����p��?��������Q\0��1go[t����Rb1D+Z�[݌Xh�#��nZVii�{�Haay?K*��#��+s�B�����d]\nhZx�Yp�v��갂nዟ���F�T���:9xx���L���~��g�:gm�s�|�#�%77B\\�\\�j��:Wn�7���\$���ΥYX���F�>	����,V�-Bް�|#��.��G�����rszFD�+;���w�,kJ�q��r����YZ�텦�bF��;fR��{`��G��{������>�%7�aO�J>��䤼vϦszW�c���Qv�����Wg�ݱ'��s�\\��w�*9�����?�W^G\n���������Z�r�L��`h��ӌ\\u3�Ba<�N.�|�	�ŏ�R4�P8ߎz����c#K\0p=�6�p�@�m�oB0L�p���L��:£��/�b�	�\r\0������K�2��A>�-4+��-J6+�����p�t!\\'^�в\\0��m���:��ϧx�ag\r�z�g��� 4.pI�����v��\n���Zl�h*���n���b6#�~OæՆ�J��a�!(4��!^�Cp;'� Cn/�r�iV�!^���mLHzZ�4uFj`�I�\0�\\\"\\&l%��Q*2獬���y�X�1��0��B���unF���Ѿ�I��t��4i�[�.\nnf�f�NR�O�jɁ\\��Vn\r6�dn���� ���\r�n\$1��\$Ql4�G��Q�b?���������Z0M���\$2F�R:`����r(�q��� u\"a._.��n";
			break;
		case "zh-tw":
			$f = "�^��%ӕ\\�r�����|%��:�\$\ns�.e�UȸE9PK72�(�P�h)ʅ@�:i	%��c�Je �R)ܫ{��	Nd T�P���\\��Õ8�C��f4����aS@/%����N����Nd�%гC��ɗB�Q+����B�_MK,�\$���u��ow�f��T9�WK��ʏW����2mizX:P	�*��_/�g*eSLK�ۈ��ι^9�H�\r���7��Zz>�����0)ȿN�\n�r!U=R�\n����^���J��T�O�](��I��^ܫ�[�f]��b����*��\\gA2��y��O�X�#�v���i`\\��\ns�P� ��h�7���P	�Z��ģBG���Tr��{4Ǒ0�&Q8)�,��ha!\0�9�0z\r��8a�^���\\0�1\\Z\r����p^8#��;̣ ^)A��T��\nt�[T�ex�!�\\\$	psd<-D%y�RP	 s-�~WF��JQO����:��(\\���1�|FM��ZS�����\0�<��(P9�*iXB m O����gANQ�D<vE��M��Q�d֭TMF��9zr��}M��) D)��8��!v]��!b���bsē�s'��UE�s݂���8*���\$�n���u���q\n�/\r�G~g1s\nb��V�����I&t�˃j�5-;#��OT�Ե1�t�V��,��Z���x����'�s���]%�)ϣ�\nƞ�i0�WDQTa���T)#�@s�xO�����Tah���1PP�\$#hV���d���¦K���J��12Af���K�G#��V*\\\\��*\r���˕I6Q0D�<C2�\$7��%��B(��J��7��\$r�MIGF���')C\$��_�IF��3-�'�V��~�O�a:�=OdQE����)\nW(�1H����'J�����ZK�y0>�ƙS:i�7���L\rN`�s�.+��E��,!�8�H�@����*�\na�7A���#\"9�>����C�C�X���\$�,1x�P�?���R�UJ�e-�����L��4&�\"�Cps���:��߇(���7�,�����L�2T>�D+Y�Z�����E�lQ\n����ح�0F�!ʱ�tO���9�ێ�a\$T`�v��r�A�'Ź�v�j0W��)�3��]�dj�š5��(���ě��	�Q	�T&���d,MP�_����\"T��Q�1,����D��0a\\p���!!B>+eX��E�r�9�\0�II�ϟQ/G0��G�����2Q���&�,#�J�rlS\nA@��I���XFÑ2/\rQ1/ÔK\n��-��3%�ę�G9�p�w���Y*''YA[�i����\"�q6��Aa<'1�9fl���<��rD��@�9����äK��h\"G4����N�#,xS\n��#�DvPLem6V訄q1�����(��@� � ���Q=W��i�`�	�\"e\0�,�R	���Ñ�qʂg�;�E\0KA|�h�)��P�5��p \n�@\"�@U�\"���m���HH��L�p�d�5u\\k��X��c+yW*��sΉ�:�슋��.'h���t�P��,�y�l�\" �s�x��mM�r���z����H!1P\"���s���\n�H�H���B�\n�3�At�0MW�T#�,����]Jy{,�S�wo�\n7I�H3����`��	.�E�\n��+���[Ӝ��2xfx��J��	b�*�\0�TX�qh(�#�g��'ey��8�B@�+&TҦ7Z��KKi�M�<B�\\U;>����͙���@��J�<B��;��/\"��\0].g<tضl�K0_V�tKo����lɅ��\n�!���|S�f�Z|R��z���>����Ur�����J)�5 	�-%j�f#����d	�~��bA,�]�I�'�\r6�P.����y���k�S��n�2� �<�m��AN'D��drrT7a#���I���9D���_�:'AX�0�P8��o�B#n*����NXAo��+E.OT	I)MT}���&'vSE�z��`��Y���H�lB��*�]{w�~�����SNbUt��֎��Z(9�B��z<�|)\r�ъ���>�ȩ1��|E�v�a�-�@yEF岌l\n�ǧ����Do&+!ev���{�q�����]r�U�.�'�����B6\r]1	�wa�\r>���������w6����}�bH�n�M���*͠��=��<y���\\�Oc�N������KMj���~�4Z�G��f����Я�����BH�e�Pk>��1엋s9\$\$�B�d���\"z_�VG�\"]N���Y\\ޑ�)�dzF��D�6����T�/��&�hF���|́<�GD�JT�>T%F��v�l��f���l��G�3��.���υ!s\r����T��YF���lf�\0S&���^\\� �{�:�v*�v��~��l�*a!	�\r\nP���X���,�S�^'�\n�n됸\\e���#q�YF���ΞY�ڣ��\nv�p�A0���΂���0��\r�\0ή(s#�M�P�<	=�&��T�*0���q.2��M����#�_��\"4f�K���ؖ��\r�ƅ��X*؏���0<AHQ�F�0��b�i�#k3C\\�%`�)��m���F� gT\r����9�>'�\\[�@2���bL�H���6\n���Z�B��j���iD�B2#b:�EN�d�,�Q\n�F僌0�68�\\�&����%�TC�|x�#)�ǈH)�q�\$&b�b�hJ��fDb��.�z+�&0l���-����n�'\nG��v�E(�&o�̖AB�i��]��%̮�6/�B0�+�2B���A,\"���+��@� ���\r� .�\0 ')Nި̸S�H�):.Q%ƹ&Rh�Q��l���1�Q�����o_�%®���,sL�A";
			break;
	}
	$Ug = array();
	foreach (explode("\n", lzw_decompress($f)) as $X) $Ug[] = (strpos($X, "\t") ? explode("\t", $X) : $X);
	return $Ug;
}
if (!$Ug) {
	$Ug = get_translations($a);
	$_SESSION["translations"] = $Ug;
}
if (extension_loaded('pdo')) {
	class
	Min_PDO
	{
		var $_result, $server_info, $affected_rows, $errno, $error, $pdo;
		function
		__construct()
		{
			global $c;
			$Ze = array_search("SQL", $c->operators);
			if ($Ze !== false) unset($c->operators[$Ze]);
		}
		function
		dsn($Ob, $V, $G, $xe = array())
		{
			$xe[PDO::ATTR_ERRMODE] = PDO::ERRMODE_SILENT;
			$xe[PDO::ATTR_STATEMENT_CLASS] = array('Min_PDOStatement');
			try {
				$this->pdo = new
					PDO($Ob, $V, $G, $xe);
			} catch (Exception $hc) {
				auth_error(h($hc->getMessage()));
			}
			$this->server_info = @$this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
		}
		function
		quote($lg)
		{
			return $this->pdo->quote($lg);
		}
		function
		query($I, $bh = false)
		{
			$J = $this->pdo->query($I);
			$this->error = "";
			if (!$J) {
				list(, $this->errno, $this->error) = $this->pdo->errorInfo();
				if (!$this->error) $this->error = lang(21);
				return
					false;
			}
			$this->store_result($J);
			return $J;
		}
		function
		multi_query($I)
		{
			return $this->_result = $this->query($I);
		}
		function
		store_result($J = null)
		{
			if (!$J) {
				$J = $this->_result;
				if (!$J) return
					false;
			}
			if ($J->columnCount()) {
				$J->num_rows = $J->rowCount();
				return $J;
			}
			$this->affected_rows = $J->rowCount();
			return
				true;
		}
		function
		next_result()
		{
			if (!$this->_result) return
				false;
			$this->_result->_offset = 0;
			return @$this->_result->nextRowset();
		}
		function
		result($I, $n = 0)
		{
			$J = $this->query($I);
			if (!$J) return
				false;
			$L = $J->fetch();
			return $L[$n];
		}
	}
	class
	Min_PDOStatement
	extends
	PDOStatement
	{
		var $_offset = 0, $num_rows;
		function
		fetch_assoc()
		{
			return $this->fetch(PDO::FETCH_ASSOC);
		}
		function
		fetch_row()
		{
			return $this->fetch(PDO::FETCH_NUM);
		}
		function
		fetch_field()
		{
			$L = (object)$this->getColumnMeta($this->_offset++);
			$L->orgtable = $L->table;
			$L->orgname = $L->name;
			$L->charsetnr = (in_array("blob", (array)$L->flags) ? 63 : 0);
			return $L;
		}
	}
}
$Kb = array();
function
add_driver($u, $E)
{
	global $Kb;
	$Kb[$u] = $E;
}
class
Min_SQL
{
	var $_conn;
	function
	__construct($g)
	{
		$this->_conn = $g;
	}
	function
	select($Q, $N, $Z, $s, $ze = array(), $_ = 1, $F = 0, $gf = false)
	{
		global $c, $y;
		$kd = (count($s) < count($N));
		$I = $c->selectQueryBuild($N, $Z, $s, $ze, $_, $F);
		if (!$I) $I = "SELECT" . limit(($_GET["page"] != "last" && $_ != "" && $s && $kd && $y == "sql" ? "SQL_CALC_FOUND_ROWS " : "") . implode(", ", $N) . "\nFROM " . table($Q), ($Z ? "\nWHERE " . implode(" AND ", $Z) : "") . ($s && $kd ? "\nGROUP BY " . implode(", ", $s) : "") . ($ze ? "\nORDER BY " . implode(", ", $ze) : ""), ($_ != "" ? +$_ : null), ($F ? $_ * $F : 0), "\n");
		$hg = microtime(true);
		$K = $this->_conn->query($I);
		if ($gf) echo $c->selectQuery($I, $hg, !$K);
		return $K;
	}
	function
	delete($Q, $of, $_ = 0)
	{
		$I = "FROM " . table($Q);
		return
			queries("DELETE" . ($_ ? limit1($Q, $I, $of) : " $I$of"));
	}
	function
	update($Q, $P, $of, $_ = 0, $Rf = "\n")
	{
		$rh = array();
		foreach ($P
			as $z => $X) $rh[] = "$z = $X";
		$I = table($Q) . " SET$Rf" . implode(",$Rf", $rh);
		return
			queries("UPDATE" . ($_ ? limit1($Q, $I, $of, $Rf) : " $I$of"));
	}
	function
	insert($Q, $P)
	{
		return
			queries("INSERT INTO " . table($Q) . ($P ? " (" . implode(", ", array_keys($P)) . ")\nVALUES (" . implode(", ", $P) . ")" : " DEFAULT VALUES"));
	}
	function
	insertUpdate($Q, $M, $ff)
	{
		return
			false;
	}
	function
	begin()
	{
		return
			queries("BEGIN");
	}
	function
	commit()
	{
		return
			queries("COMMIT");
	}
	function
	rollback()
	{
		return
			queries("ROLLBACK");
	}
	function
	slowQuery($I, $Hg)
	{
	}
	function
	convertSearch($v, $X, $n)
	{
		return $v;
	}
	function
	value($X, $n)
	{
		return (method_exists($this->_conn, 'value') ? $this->_conn->value($X, $n) : (is_resource($X) ? stream_get_contents($X) : $X));
	}
	function
	quoteBinary($If)
	{
		return
			q($If);
	}
	function
	warnings()
	{
		return '';
	}
	function
	tableHelp($E)
	{
	}
}
class
Adminer
{
	var $operators;
	function
	name()
	{
		return "<a href='https://www.adminer.org/'" . target_blank() . " id='h1'>Adminer</a>";
	}
	function
	credentials()
	{
		return
			array(SERVER, $_GET["username"], get_password());
	}
	function
	connectSsl()
	{
	}
	function
	permanentLogin($i = false)
	{
		return
			password_file($i);
	}
	function
	bruteForceKey()
	{
		return $_SERVER["REMOTE_ADDR"];
	}
	function
	serverName($O)
	{
		return
			h($O);
	}
	function
	database()
	{
		return
			DB;
	}
	function
	databases($yc = true)
	{
		return
			get_databases($yc);
	}
	function
	schemas()
	{
		return
			schemas();
	}
	function
	queryTimeout()
	{
		return
			2;
	}
	function
	headers()
	{
	}
	function
	csp()
	{
		return
			csp();
	}
	function
	head()
	{
		return
			true;
	}
	function
	css()
	{
		$K = array();
		$vc = "adminer.css";
		if (file_exists($vc)) $K[] = "$vc?v=" . crc32(file_get_contents($vc));
		return $K;
	}
	function
	loginForm()
	{
		global $Kb;
		echo "<table cellspacing='0' class='layout'>\n", $this->loginFormField('driver', '<tr><th>' . lang(22) . '<td>', html_select("auth[driver]", $Kb, DRIVER, "loginDriver(this);") . "\n"), $this->loginFormField('server', '<tr><th>' . lang(23) . '<td>', '<input name="auth[server]" value="' . h(SERVER) . '" title="hostname[:port]" placeholder="localhost" autocapitalize="off">' . "\n"), $this->loginFormField('username', '<tr><th>' . lang(24) . '<td>', '<input name="auth[username]" id="username" value="' . h($_GET["username"]) . '" autocomplete="username" autocapitalize="off">' . script("focus(qs('#username')); qs('#username').form['auth[driver]'].onchange();")), $this->loginFormField('password', '<tr><th>' . lang(25) . '<td>', '<input type="password" name="auth[password]" autocomplete="current-password">' . "\n"), $this->loginFormField('db', '<tr><th>' . lang(26) . '<td>', '<input name="auth[db]" value="' . h($_GET["db"]) . '" autocapitalize="off">' . "\n"), "</table>\n", "<p><input type='submit' value='" . lang(27) . "'>\n", checkbox("auth[permanent]", 1, $_COOKIE["adminer_permanent"], lang(28)) . "\n";
	}
	function
	loginFormField($E, $Sc, $Y)
	{
		return $Sc . $Y;
	}
	function
	login($Fd, $G)
	{
		if ($G == "") return
			lang(29, target_blank());
		return
			true;
	}
	function
	tableName($tg)
	{
		return
			h($tg["Name"]);
	}
	function
	fieldName($n, $ze = 0)
	{
		return '<span title="' . h($n["full_type"]) . '">' . h($n["field"]) . '</span>';
	}
	function
	selectLinks($tg, $P = "")
	{
		global $y, $l;
		echo '<p class="links">';
		$Ed = array("select" => lang(30));
		if (support("table") || support("indexes")) $Ed["table"] = lang(31);
		if (support("table")) {
			if (is_view($tg)) $Ed["view"] = lang(32);
			else $Ed["create"] = lang(33);
		}
		if ($P !== null) $Ed["edit"] = lang(34);
		$E = $tg["Name"];
		foreach ($Ed
			as $z => $X) echo " <a href='" . h(ME) . "$z=" . urlencode($E) . ($z == "edit" ? $P : "") . "'" . bold(isset($_GET[$z])) . ">$X</a>";
		echo
		doc_link(array($y => $l->tableHelp($E)), "?"), "\n";
	}
	function
	foreignKeys($Q)
	{
		return
			foreign_keys($Q);
	}
	function
	backwardKeys($Q, $sg)
	{
		return
			array();
	}
	function
	backwardKeysPrint($Aa, $L)
	{
	}
	function
	selectQuery($I, $hg, $qc = false)
	{
		global $y, $l;
		$K = "</p>\n";
		if (!$qc && ($zh = $l->warnings())) {
			$u = "warnings";
			$K = ", <a href='#$u'>" . lang(35) . "</a>" . script("qsl('a').onclick = partial(toggle, '$u');", "") . "$K<div id='$u' class='hidden'>\n$zh</div>\n";
		}
		return "<p><code class='jush-$y'>" . h(str_replace("\n", " ", $I)) . "</code> <span class='time'>(" . format_time($hg) . ")</span>" . (support("sql") ? " <a href='" . h(ME) . "sql=" . urlencode($I) . "'>" . lang(10) . "</a>" : "") . $K;
	}
	function
	sqlCommandQuery($I)
	{
		return
			shorten_utf8(trim($I), 1000);
	}
	function
	rowDescription($Q)
	{
		return "";
	}
	function
	rowDescriptions($M, $Ac)
	{
		return $M;
	}
	function
	selectLink($X, $n)
	{
	}
	function
	selectVal($X, $A, $n, $Ge)
	{
		$K = ($X === null ? "<i>NULL</i>" : (preg_match("~char|binary|boolean~", $n["type"]) && !preg_match("~var~", $n["type"]) ? "<code>$X</code>" : $X));
		if (preg_match('~blob|bytea|raw|file~', $n["type"]) && !is_utf8($X)) $K = "<i>" . lang(36, strlen($Ge)) . "</i>";
		if (preg_match('~json~', $n["type"])) $K = "<code class='jush-js'>$K</code>";
		return ($A ? "<a href='" . h($A) . "'" . (is_url($A) ? target_blank() : "") . ">$K</a>" : $K);
	}
	function
	editVal($X, $n)
	{
		return $X;
	}
	function
	tableStructurePrint($o)
	{
		echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap'>\n", "<thead><tr><th>" . lang(37) . "<td>" . lang(38) . (support("comment") ? "<td>" . lang(39) : "") . "</thead>\n";
		foreach ($o
			as $n) {
			echo "<tr" . odd() . "><th>" . h($n["field"]), "<td><span title='" . h($n["collation"]) . "'>" . h($n["full_type"]) . "</span>", ($n["null"] ? " <i>NULL</i>" : ""), ($n["auto_increment"] ? " <i>" . lang(40) . "</i>" : ""), (isset($n["default"]) ? " <span title='" . lang(41) . "'>[<b>" . h($n["default"]) . "</b>]</span>" : ""), (support("comment") ? "<td>" . h($n["comment"]) : ""), "\n";
		}
		echo "</table>\n", "</div>\n";
	}
	function
	tableIndexesPrint($x)
	{
		echo "<table cellspacing='0'>\n";
		foreach ($x
			as $E => $w) {
			ksort($w["columns"]);
			$gf = array();
			foreach ($w["columns"] as $z => $X) $gf[] = "<i>" . h($X) . "</i>" . ($w["lengths"][$z] ? "(" . $w["lengths"][$z] . ")" : "") . ($w["descs"][$z] ? " DESC" : "");
			echo "<tr title='" . h($E) . "'><th>$w[type]<td>" . implode(", ", $gf) . "\n";
		}
		echo "</table>\n";
	}
	function
	selectColumnsPrint($N, $e)
	{
		global $Gc, $Lc;
		print_fieldset("select", lang(42), $N);
		$t = 0;
		$N[""] = array();
		foreach ($N
			as $z => $X) {
			$X = $_GET["columns"][$z];
			$d = select_input(" name='columns[$t][col]'", $e, $X["col"], ($z !== "" ? "selectFieldChange" : "selectAddRow"));
			echo "<div>" . ($Gc || $Lc ? "<select name='columns[$t][fun]'>" . optionlist(array(-1 => "") + array_filter(array(lang(43) => $Gc, lang(44) => $Lc)), $X["fun"]) . "</select>" . on_help("getTarget(event).value && getTarget(event).value.replace(/ |\$/, '(') + ')'", 1) . script("qsl('select').onchange = function () { helpClose();" . ($z !== "" ? "" : " qsl('select, input', this.parentNode).onchange();") . " };", "") . "($d)" : $d) . "</div>\n";
			$t++;
		}
		echo "</div></fieldset>\n";
	}
	function
	selectSearchPrint($Z, $e, $x)
	{
		print_fieldset("search", lang(45), $Z);
		foreach ($x
			as $t => $w) {
			if ($w["type"] == "FULLTEXT") {
				echo "<div>(<i>" . implode("</i>, <i>", array_map('h', $w["columns"])) . "</i>) AGAINST", " <input type='search' name='fulltext[$t]' value='" . h($_GET["fulltext"][$t]) . "'>", script("qsl('input').oninput = selectFieldChange;", ""), checkbox("boolean[$t]", 1, isset($_GET["boolean"][$t]), "BOOL"), "</div>\n";
			}
		}
		$Ka = "this.parentNode.firstChild.onchange();";
		foreach (array_merge((array)$_GET["where"], array(array())) as $t => $X) {
			if (!$X || ("$X[col]$X[val]" != "" && in_array($X["op"], $this->operators))) {
				echo "<div>" . select_input(" name='where[$t][col]'", $e, $X["col"], ($X ? "selectFieldChange" : "selectAddRow"), "(" . lang(46) . ")"), html_select("where[$t][op]", $this->operators, $X["op"], $Ka), "<input type='search' name='where[$t][val]' value='" . h($X["val"]) . "'>", script("mixin(qsl('input'), {oninput: function () { $Ka }, onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});", ""), "</div>\n";
			}
		}
		echo "</div></fieldset>\n";
	}
	function
	selectOrderPrint($ze, $e, $x)
	{
		print_fieldset("sort", lang(47), $ze);
		$t = 0;
		foreach ((array)$_GET["order"] as $z => $X) {
			if ($X != "") {
				echo "<div>" . select_input(" name='order[$t]'", $e, $X, "selectFieldChange"), checkbox("desc[$t]", 1, isset($_GET["desc"][$z]), lang(48)) . "</div>\n";
				$t++;
			}
		}
		echo "<div>" . select_input(" name='order[$t]'", $e, "", "selectAddRow"), checkbox("desc[$t]", 1, false, lang(48)) . "</div>\n", "</div></fieldset>\n";
	}
	function
	selectLimitPrint($_)
	{
		echo "<fieldset><legend>" . lang(49) . "</legend><div>";
		echo "<input type='number' name='limit' class='size' value='" . h($_) . "'>", script("qsl('input').oninput = selectFieldChange;", ""), "</div></fieldset>\n";
	}
	function
	selectLengthPrint($Fg)
	{
		if ($Fg !== null) {
			echo "<fieldset><legend>" . lang(50) . "</legend><div>", "<input type='number' name='text_length' class='size' value='" . h($Fg) . "'>", "</div></fieldset>\n";
		}
	}
	function
	selectActionPrint($x)
	{
		echo "<fieldset><legend>" . lang(51) . "</legend><div>", "<input type='submit' value='" . lang(42) . "'>", " <span id='noindex' title='" . lang(52) . "'></span>", "<script" . nonce() . ">\n", "var indexColumns = ";
		$e = array();
		foreach ($x
			as $w) {
			$rb = reset($w["columns"]);
			if ($w["type"] != "FULLTEXT" && $rb) $e[$rb] = 1;
		}
		$e[""] = 1;
		foreach ($e
			as $z => $X) json_row($z);
		echo ";\n", "selectFieldChange.call(qs('#form')['select']);\n", "</script>\n", "</div></fieldset>\n";
	}
	function
	selectCommandPrint()
	{
		return !information_schema(DB);
	}
	function
	selectImportPrint()
	{
		return !information_schema(DB);
	}
	function
	selectEmailPrint($Wb, $e)
	{
	}
	function
	selectColumnsProcess($e, $x)
	{
		global $Gc, $Lc;
		$N = array();
		$s = array();
		foreach ((array)$_GET["columns"] as $z => $X) {
			if ($X["fun"] == "count" || ($X["col"] != "" && (!$X["fun"] || in_array($X["fun"], $Gc) || in_array($X["fun"], $Lc)))) {
				$N[$z] = apply_sql_function($X["fun"], ($X["col"] != "" ? idf_escape($X["col"]) : "*"));
				if (!in_array($X["fun"], $Lc)) $s[] = $N[$z];
			}
		}
		return
			array($N, $s);
	}
	function
	selectSearchProcess($o, $x)
	{
		global $g, $l;
		$K = array();
		foreach ($x
			as $t => $w) {
			if ($w["type"] == "FULLTEXT" && $_GET["fulltext"][$t] != "") $K[] = "MATCH (" . implode(", ", array_map('idf_escape', $w["columns"])) . ") AGAINST (" . q($_GET["fulltext"][$t]) . (isset($_GET["boolean"][$t]) ? " IN BOOLEAN MODE" : "") . ")";
		}
		foreach ((array)$_GET["where"] as $z => $X) {
			if ("$X[col]$X[val]" != "" && in_array($X["op"], $this->operators)) {
				$df = "";
				$db = " $X[op]";
				if (preg_match('~IN$~', $X["op"])) {
					$ad = process_length($X["val"]);
					$db .= " " . ($ad != "" ? $ad : "(NULL)");
				} elseif ($X["op"] == "SQL") $db = " $X[val]";
				elseif ($X["op"] == "LIKE %%") $db = " LIKE " . $this->processInput($o[$X["col"]], "%$X[val]%");
				elseif ($X["op"] == "ILIKE %%") $db = " ILIKE " . $this->processInput($o[$X["col"]], "%$X[val]%");
				elseif ($X["op"] == "FIND_IN_SET") {
					$df = "$X[op](" . q($X["val"]) . ", ";
					$db = ")";
				} elseif (!preg_match('~NULL$~', $X["op"])) $db .= " " . $this->processInput($o[$X["col"]], $X["val"]);
				if ($X["col"] != "") $K[] = $df . $l->convertSearch(idf_escape($X["col"]), $X, $o[$X["col"]]) . $db;
				else {
					$Ya = array();
					foreach ($o
						as $E => $n) {
						if ((preg_match('~^[-\d.' . (preg_match('~IN$~', $X["op"]) ? ',' : '') . ']+$~', $X["val"]) || !preg_match('~' . number_type() . '|bit~', $n["type"])) && (!preg_match("~[\x80-\xFF]~", $X["val"]) || preg_match('~char|text|enum|set~', $n["type"])) && (!preg_match('~date|timestamp~', $n["type"]) || preg_match('~^\d+-\d+-\d+~', $X["val"]))) $Ya[] = $df . $l->convertSearch(idf_escape($E), $X, $n) . $db;
					}
					$K[] = ($Ya ? "(" . implode(" OR ", $Ya) . ")" : "1 = 0");
				}
			}
		}
		return $K;
	}
	function
	selectOrderProcess($o, $x)
	{
		$K = array();
		foreach ((array)$_GET["order"] as $z => $X) {
			if ($X != "") $K[] = (preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~', $X) ? $X : idf_escape($X)) . (isset($_GET["desc"][$z]) ? " DESC" : "");
		}
		return $K;
	}
	function
	selectLimitProcess()
	{
		return (isset($_GET["limit"]) ? $_GET["limit"] : "50");
	}
	function
	selectLengthProcess()
	{
		return (isset($_GET["text_length"]) ? $_GET["text_length"] : "100");
	}
	function
	selectEmailProcess($Z, $Ac)
	{
		return
			false;
	}
	function
	selectQueryBuild($N, $Z, $s, $ze, $_, $F)
	{
		return "";
	}
	function
	messageQuery($I, $Gg, $qc = false)
	{
		global $y, $l;
		restart_session();
		$Tc = &get_session("queries");
		if (!$Tc[$_GET["db"]]) $Tc[$_GET["db"]] = array();
		if (strlen($I) > 1e6) $I = preg_replace('~[\x80-\xFF]+$~', '', substr($I, 0, 1e6)) . "\n…";
		$Tc[$_GET["db"]][] = array($I, time(), $Gg);
		$fg = "sql-" . count($Tc[$_GET["db"]]);
		$K = "<a href='#$fg' class='toggle'>" . lang(53) . "</a>\n";
		if (!$qc && ($zh = $l->warnings())) {
			$u = "warnings-" . count($Tc[$_GET["db"]]);
			$K = "<a href='#$u' class='toggle'>" . lang(35) . "</a>, $K<div id='$u' class='hidden'>\n$zh</div>\n";
		}
		return " <span class='time'>" . @date("H:i:s") . "</span>" . " $K<div id='$fg' class='hidden'><pre><code class='jush-$y'>" . shorten_utf8($I, 1000) . "</code></pre>" . ($Gg ? " <span class='time'>($Gg)</span>" : '') . (support("sql") ? '<p><a href="' . h(str_replace("db=" . urlencode(DB), "db=" . urlencode($_GET["db"]), ME) . 'sql=&history=' . (count($Tc[$_GET["db"]]) - 1)) . '">' . lang(10) . '</a>' : '') . '</div>';
	}
	function
	editRowPrint($Q, $o, $L, $ih)
	{
	}
	function
	editFunctions($n)
	{
		global $Rb;
		$K = ($n["null"] ? "NULL/" : "");
		$ih = isset($_GET["select"]) || where($_GET);
		foreach ($Rb
			as $z => $Gc) {
			if (!$z || (!isset($_GET["call"]) && $ih)) {
				foreach ($Gc
					as $Ue => $X) {
					if (!$Ue || preg_match("~$Ue~", $n["type"])) $K .= "/$X";
				}
			}
			if ($z && !preg_match('~set|blob|bytea|raw|file|bool~', $n["type"])) $K .= "/SQL";
		}
		if ($n["auto_increment"] && !$ih) $K = lang(40);
		return
			explode("/", $K);
	}
	function
	editInput($Q, $n, $wa, $Y)
	{
		if ($n["type"] == "enum") return (isset($_GET["select"]) ? "<label><input type='radio'$wa value='-1' checked><i>" . lang(8) . "</i></label> " : "") . ($n["null"] ? "<label><input type='radio'$wa value=''" . ($Y !== null || isset($_GET["select"]) ? "" : " checked") . "><i>NULL</i></label> " : "") . enum_input("radio", $wa, $n, $Y, 0);
		return "";
	}
	function
	editHint($Q, $n, $Y)
	{
		return "";
	}
	function
	processInput($n, $Y, $r = "")
	{
		if ($r == "SQL") return $Y;
		$E = $n["field"];
		$K = q($Y);
		if (preg_match('~^(now|getdate|uuid)$~', $r)) $K = "$r()";
		elseif (preg_match('~^current_(date|timestamp)$~', $r)) $K = $r;
		elseif (preg_match('~^([+-]|\|\|)$~', $r)) $K = idf_escape($E) . " $r $K";
		elseif (preg_match('~^[+-] interval$~', $r)) $K = idf_escape($E) . " $r " . (preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i", $Y) ? $Y : $K);
		elseif (preg_match('~^(addtime|subtime|concat)$~', $r)) $K = "$r(" . idf_escape($E) . ", $K)";
		elseif (preg_match('~^(md5|sha1|password|encrypt)$~', $r)) $K = "$r($K)";
		return
			unconvert_field($n, $K);
	}
	function
	dumpOutput()
	{
		$K = array('text' => lang(54), 'file' => lang(55));
		if (function_exists('gzencode')) $K['gz'] = 'gzip';
		return $K;
	}
	function
	dumpFormat()
	{
		return
			array('sql' => 'SQL', 'csv' => 'CSV,', 'csv;' => 'CSV;', 'tsv' => 'TSV');
	}
	function
	dumpDatabase($k)
	{
	}
	function
	dumpTable($Q, $ng, $md = 0)
	{
		if ($_POST["format"] != "sql") {
			echo "\xef\xbb\xbf";
			if ($ng) dump_csv(array_keys(fields($Q)));
		} else {
			if ($md == 2) {
				$o = array();
				foreach (fields($Q) as $E => $n) $o[] = idf_escape($E) . " $n[full_type]";
				$i = "CREATE TABLE " . table($Q) . " (" . implode(", ", $o) . ")";
			} else $i = create_sql($Q, $_POST["auto_increment"], $ng);
			set_utf8mb4($i);
			if ($ng && $i) {
				if ($ng == "DROP+CREATE" || $md == 1) echo "DROP " . ($md == 2 ? "VIEW" : "TABLE") . " IF EXISTS " . table($Q) . ";\n";
				if ($md == 1) $i = remove_definer($i);
				echo "$i;\n\n";
			}
		}
	}
	function
	dumpData($Q, $ng, $I)
	{
		global $g, $y;
		$Ld = ($y == "sqlite" ? 0 : 1048576);
		if ($ng) {
			if ($_POST["format"] == "sql") {
				if ($ng == "TRUNCATE+INSERT") echo
				truncate_sql($Q) . ";\n";
				$o = fields($Q);
			}
			$J = $g->query($I, 1);
			if ($J) {
				$fd = "";
				$Ia = "";
				$pd = array();
				$pg = "";
				$tc = ($Q != '' ? 'fetch_assoc' : 'fetch_row');
				while ($L = $J->$tc()) {
					if (!$pd) {
						$rh = array();
						foreach ($L
							as $X) {
							$n = $J->fetch_field();
							$pd[] = $n->name;
							$z = idf_escape($n->name);
							$rh[] = "$z = VALUES($z)";
						}
						$pg = ($ng == "INSERT+UPDATE" ? "\nON DUPLICATE KEY UPDATE " . implode(", ", $rh) : "") . ";\n";
					}
					if ($_POST["format"] != "sql") {
						if ($ng == "table") {
							dump_csv($pd);
							$ng = "INSERT";
						}
						dump_csv($L);
					} else {
						if (!$fd) $fd = "INSERT INTO " . table($Q) . " (" . implode(", ", array_map('idf_escape', $pd)) . ") VALUES";
						foreach ($L
							as $z => $X) {
							$n = $o[$z];
							$L[$z] = ($X !== null ? unconvert_field($n, preg_match(number_type(), $n["type"]) && !preg_match('~\[~', $n["full_type"]) && is_numeric($X) ? $X : q(($X === false ? 0 : $X))) : "NULL");
						}
						$If = ($Ld ? "\n" : " ") . "(" . implode(",\t", $L) . ")";
						if (!$Ia) $Ia = $fd . $If;
						elseif (strlen($Ia) + 4 + strlen($If) + strlen($pg) < $Ld) $Ia .= ",$If";
						else {
							echo $Ia . $pg;
							$Ia = $fd . $If;
						}
					}
				}
				if ($Ia) echo $Ia . $pg;
			} elseif ($_POST["format"] == "sql") echo "-- " . str_replace("\n", " ", $g->error) . "\n";
		}
	}
	function
	dumpFilename($Xc)
	{
		return
			friendly_url($Xc != "" ? $Xc : (SERVER != "" ? SERVER : "localhost"));
	}
	function
	dumpHeaders($Xc, $Xd = false)
	{
		$Ie = $_POST["output"];
		$nc = (preg_match('~sql~', $_POST["format"]) ? "sql" : ($Xd ? "tar" : "csv"));
		header("Content-Type: " . ($Ie == "gz" ? "application/x-gzip" : ($nc == "tar" ? "application/x-tar" : ($nc == "sql" || $Ie != "file" ? "text/plain" : "text/csv") . "; charset=utf-8")));
		if ($Ie == "gz") ob_start('ob_gzencode', 1e6);
		return $nc;
	}
	function
	importServerPath()
	{
		return "adminer.sql";
	}
	function
	homepage()
	{
		echo '<p class="links">' . ($_GET["ns"] == "" && support("database") ? '<a href="' . h(ME) . 'database=">' . lang(56) . "</a>\n" : ""), (support("scheme") ? "<a href='" . h(ME) . "scheme='>" . ($_GET["ns"] != "" ? lang(57) : lang(58)) . "</a>\n" : ""), ($_GET["ns"] !== "" ? '<a href="' . h(ME) . 'schema=">' . lang(59) . "</a>\n" : ""), (support("privileges") ? "<a href='" . h(ME) . "privileges='>" . lang(60) . "</a>\n" : "");
		return
			true;
	}
	function
	navigation($Wd)
	{
		global $fa, $y, $Kb, $g;
		echo '<h1>
', $this->name(), ' <span class="version">', $fa, '</span>
<a href="https://www.adminer.org/#download"', target_blank(), ' id="version">', (version_compare($fa, $_COOKIE["adminer_version"]) < 0 ? h($_COOKIE["adminer_version"]) : ""), '</a>
</h1>
';
		if ($Wd == "auth") {
			$Ie = "";
			foreach ((array)$_SESSION["pwds"] as $th => $Tf) {
				foreach ($Tf
					as $O => $ph) {
					foreach ($ph
						as $V => $G) {
						if ($G !== null) {
							$xb = $_SESSION["db"][$th][$O][$V];
							foreach (($xb ? array_keys($xb) : array("")) as $k) $Ie .= "<li><a href='" . h(auth_url($th, $O, $V, $k)) . "'>($Kb[$th]) " . h($V . ($O != "" ? "@" . $this->serverName($O) : "") . ($k != "" ? " - $k" : "")) . "</a>\n";
						}
					}
				}
			}
			if ($Ie) echo "<ul id='logins'>\n$Ie</ul>\n" . script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");
		} else {
			$S = array();
			if ($_GET["ns"] !== "" && !$Wd && DB != "") {
				$g->select_db(DB);
				$S = table_status('', true);
			}
			echo
			script_src(preg_replace("~\\?.*~", "", ME) . "?file=jush.js&version=4.8.1");
			if (support("sql")) {
				echo '<script', nonce(), '>
';
				if ($S) {
					$Ed = array();
					foreach ($S
						as $Q => $U) $Ed[] = preg_quote($Q, '/');
					echo "var jushLinks = { $y: [ '" . js_escape(ME) . (support("table") ? "table=" : "select=") . "\$&', /\\b(" . implode("|", $Ed) . ")\\b/g ] };\n";
					foreach (array("bac", "bra", "sqlite_quo", "mssql_bra") as $X) echo "jushLinks.$X = jushLinks.$y;\n";
				}
				$Sf = $g->server_info;
				echo 'bodyLoad(\'', (is_object($g) ? preg_replace('~^(\d\.?\d).*~s', '\1', $Sf) : ""), '\'', (preg_match('~MariaDB~', $Sf) ? ", true" : ""), ');
</script>
';
			}
			$this->databasesPrint($Wd);
			if (DB == "" || !$Wd) {
				echo "<p class='links'>" . (support("sql") ? "<a href='" . h(ME) . "sql='" . bold(isset($_GET["sql"]) && !isset($_GET["import"])) . ">" . lang(53) . "</a>\n<a href='" . h(ME) . "import='" . bold(isset($_GET["import"])) . ">" . lang(61) . "</a>\n" : "") . "";
				if (support("dump")) echo "<a href='" . h(ME) . "dump=" . urlencode(isset($_GET["table"]) ? $_GET["table"] : $_GET["select"]) . "' id='dump'" . bold(isset($_GET["dump"])) . ">" . lang(62) . "</a>\n";
			}
			if ($_GET["ns"] !== "" && !$Wd && DB != "") {
				echo '<a href="' . h(ME) . 'create="' . bold($_GET["create"] === "") . ">" . lang(63) . "</a>\n";
				if (!$S) echo "<p class='message'>" . lang(9) . "\n";
				else $this->tablesPrint($S);
			}
		}
	}
	function
	databasesPrint($Wd)
	{
		global $c, $g;
		$j = $this->databases();
		if (DB && $j && !in_array(DB, $j)) array_unshift($j, DB);
		echo '<form action="">
<p id="dbs">
';
		hidden_fields_get();
		$vb = script("mixin(qsl('select'), {onmousedown: dbMouseDown, onchange: dbChange});");
		echo "<span title='" . lang(64) . "'>" . lang(65) . "</span>: " . ($j ? "<select name='db'>" . optionlist(array("" => "") + $j, DB) . "</select>$vb" : "<input name='db' value='" . h(DB) . "' autocapitalize='off'>\n"), "<input type='submit' value='" . lang(20) . "'" . ($j ? " class='hidden'" : "") . ">\n";
		foreach (array("import", "sql", "schema", "dump", "privileges") as $X) {
			if (isset($_GET[$X])) {
				echo "<input type='hidden' name='$X' value=''>";
				break;
			}
		}
		echo "</p></form>\n";
	}
	function
	tablesPrint($S)
	{
		echo "<ul id='tables'>" . script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");
		foreach ($S
			as $Q => $ig) {
			$E = $this->tableName($ig);
			if ($E != "") {
				echo '<li><a href="' . h(ME) . 'select=' . urlencode($Q) . '"' . bold($_GET["select"] == $Q || $_GET["edit"] == $Q, "select") . " title='" . lang(30) . "'>" . lang(66) . "</a> ", (support("table") || support("indexes") ? '<a href="' . h(ME) . 'table=' . urlencode($Q) . '"' . bold(in_array($Q, array($_GET["table"], $_GET["create"], $_GET["indexes"], $_GET["foreign"], $_GET["trigger"])), (is_view($ig) ? "view" : "structure")) . " title='" . lang(31) . "'>$E</a>" : "<span>$E</span>") . "\n";
			}
		}
		echo "</ul>\n";
	}
}
$c = (function_exists('adminer_object') ? adminer_object() : new
	Adminer);
$Kb = array("server" => "MySQL") + $Kb;
if (!defined("DRIVER")) {
	define("DRIVER", "server");
	if (extension_loaded("mysqli")) {
		class
		Min_DB
		extends
		MySQLi
		{
			var $extension = "MySQLi";
			function
			__construct()
			{
				parent::init();
			}
			function
			connect($O = "", $V = "", $G = "", $ub = null, $Ye = null, $ag = null)
			{
				global $c;
				mysqli_report(MYSQLI_REPORT_OFF);
				list($Vc, $Ye) = explode(":", $O, 2);
				$gg = $c->connectSsl();
				if ($gg) $this->ssl_set($gg['key'], $gg['cert'], $gg['ca'], '', '');
				$K = @$this->real_connect(($O != "" ? $Vc : ini_get("mysqli.default_host")), ($O . $V != "" ? $V : ini_get("mysqli.default_user")), ($O . $V . $G != "" ? $G : ini_get("mysqli.default_pw")), $ub, (is_numeric($Ye) ? $Ye : ini_get("mysqli.default_port")), (!is_numeric($Ye) ? $Ye : $ag), ($gg ? 64 : 0));
				$this->options(MYSQLI_OPT_LOCAL_INFILE, false);
				return $K;
			}
			function
			set_charset($La)
			{
				if (parent::set_charset($La)) return
					true;
				parent::set_charset('utf8');
				return $this->query("SET NAMES $La");
			}
			function
			result($I, $n = 0)
			{
				$J = $this->query($I);
				if (!$J) return
					false;
				$L = $J->fetch_array();
				return $L[$n];
			}
			function
			quote($lg)
			{
				return "'" . $this->escape_string($lg) . "'";
			}
		}
	} elseif (extension_loaded("mysql") && !((ini_bool("sql.safe_mode") || ini_bool("mysql.allow_local_infile")) && extension_loaded("pdo_mysql"))) {
		class
		Min_DB
		{
			var $extension = "MySQL", $server_info, $affected_rows, $errno, $error, $_link, $_result;
			function
			connect($O, $V, $G)
			{
				if (ini_bool("mysql.allow_local_infile")) {
					$this->error = lang(67, "'mysql.allow_local_infile'", "MySQLi", "PDO_MySQL");
					return
						false;
				}
				$this->_link = @mysql_connect(($O != "" ? $O : ini_get("mysql.default_host")), ("$O$V" != "" ? $V : ini_get("mysql.default_user")), ("$O$V$G" != "" ? $G : ini_get("mysql.default_password")), true, 131072);
				if ($this->_link) $this->server_info = mysql_get_server_info($this->_link);
				else $this->error = mysql_error();
				return (bool)$this->_link;
			}
			function
			set_charset($La)
			{
				if (function_exists('mysql_set_charset')) {
					if (mysql_set_charset($La, $this->_link)) return
						true;
					mysql_set_charset('utf8', $this->_link);
				}
				return $this->query("SET NAMES $La");
			}
			function
			quote($lg)
			{
				return "'" . mysql_real_escape_string($lg, $this->_link) . "'";
			}
			function
			select_db($ub)
			{
				return
					mysql_select_db($ub, $this->_link);
			}
			function
			query($I, $bh = false)
			{
				$J = @($bh ? mysql_unbuffered_query($I, $this->_link) : mysql_query($I, $this->_link));
				$this->error = "";
				if (!$J) {
					$this->errno = mysql_errno($this->_link);
					$this->error = mysql_error($this->_link);
					return
						false;
				}
				if ($J === true) {
					$this->affected_rows = mysql_affected_rows($this->_link);
					$this->info = mysql_info($this->_link);
					return
						true;
				}
				return
					new
					Min_Result($J);
			}
			function
			multi_query($I)
			{
				return $this->_result = $this->query($I);
			}
			function
			store_result()
			{
				return $this->_result;
			}
			function
			next_result()
			{
				return
					false;
			}
			function
			result($I, $n = 0)
			{
				$J = $this->query($I);
				if (!$J || !$J->num_rows) return
					false;
				return
					mysql_result($J->_result, 0, $n);
			}
		}
		class
		Min_Result
		{
			var $num_rows, $_result, $_offset = 0;
			function
			__construct($J)
			{
				$this->_result = $J;
				$this->num_rows = mysql_num_rows($J);
			}
			function
			fetch_assoc()
			{
				return
					mysql_fetch_assoc($this->_result);
			}
			function
			fetch_row()
			{
				return
					mysql_fetch_row($this->_result);
			}
			function
			fetch_field()
			{
				$K = mysql_fetch_field($this->_result, $this->_offset++);
				$K->orgtable = $K->table;
				$K->orgname = $K->name;
				$K->charsetnr = ($K->blob ? 63 : 0);
				return $K;
			}
			function
			__destruct()
			{
				mysql_free_result($this->_result);
			}
		}
	} elseif (extension_loaded("pdo_mysql")) {
		class
		Min_DB
		extends
		Min_PDO
		{
			var $extension = "PDO_MySQL";
			function
			connect($O, $V, $G)
			{
				global $c;
				$xe = array(PDO::MYSQL_ATTR_LOCAL_INFILE => false);
				$gg = $c->connectSsl();
				if ($gg) {
					if (!empty($gg['key'])) $xe[PDO::MYSQL_ATTR_SSL_KEY] = $gg['key'];
					if (!empty($gg['cert'])) $xe[PDO::MYSQL_ATTR_SSL_CERT] = $gg['cert'];
					if (!empty($gg['ca'])) $xe[PDO::MYSQL_ATTR_SSL_CA] = $gg['ca'];
				}
				$this->dsn("mysql:charset=utf8;host=" . str_replace(":", ";unix_socket=", preg_replace('~:(\d)~', ';port=\1', $O)), $V, $G, $xe);
				return
					true;
			}
			function
			set_charset($La)
			{
				$this->query("SET NAMES $La");
			}
			function
			select_db($ub)
			{
				return $this->query("USE " . idf_escape($ub));
			}
			function
			query($I, $bh = false)
			{
				$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, !$bh);
				return
					parent::query($I, $bh);
			}
		}
	}
	class
	Min_Driver
	extends
	Min_SQL
	{
		function
		insert($Q, $P)
		{
			return ($P ? parent::insert($Q, $P) : queries("INSERT INTO " . table($Q) . " ()\nVALUES ()"));
		}
		function
		insertUpdate($Q, $M, $ff)
		{
			$e = array_keys(reset($M));
			$df = "INSERT INTO " . table($Q) . " (" . implode(", ", $e) . ") VALUES\n";
			$rh = array();
			foreach ($e
				as $z) $rh[$z] = "$z = VALUES($z)";
			$pg = "\nON DUPLICATE KEY UPDATE " . implode(", ", $rh);
			$rh = array();
			$Bd = 0;
			foreach ($M
				as $P) {
				$Y = "(" . implode(", ", $P) . ")";
				if ($rh && (strlen($df) + $Bd + strlen($Y) + strlen($pg) > 1e6)) {
					if (!queries($df . implode(",\n", $rh) . $pg)) return
						false;
					$rh = array();
					$Bd = 0;
				}
				$rh[] = $Y;
				$Bd += strlen($Y) + 2;
			}
			return
				queries($df . implode(",\n", $rh) . $pg);
		}
		function
		slowQuery($I, $Hg)
		{
			if (min_version('5.7.8', '10.1.2')) {
				if (preg_match('~MariaDB~', $this->_conn->server_info)) return "SET STATEMENT max_statement_time=$Hg FOR $I";
				elseif (preg_match('~^(SELECT\b)(.+)~is', $I, $C)) return "$C[1] /*+ MAX_EXECUTION_TIME(" . ($Hg * 1000) . ") */ $C[2]";
			}
		}
		function
		convertSearch($v, $X, $n)
		{
			return (preg_match('~char|text|enum|set~', $n["type"]) && !preg_match("~^utf8~", $n["collation"]) && preg_match('~[\x80-\xFF]~', $X['val']) ? "CONVERT($v USING " . charset($this->_conn) . ")" : $v);
		}
		function
		warnings()
		{
			$J = $this->_conn->query("SHOW WARNINGS");
			if ($J && $J->num_rows) {
				ob_start();
				select($J);
				return
					ob_get_clean();
			}
		}
		function
		tableHelp($E)
		{
			$Hd = preg_match('~MariaDB~', $this->_conn->server_info);
			if (information_schema(DB)) return
				strtolower(($Hd ? "information-schema-$E-table/" : str_replace("_", "-", $E) . "-table.html"));
			if (DB == "mysql") return ($Hd ? "mysql$E-table/" : "system-database.html");
		}
	}
	function
	idf_escape($v)
	{
		return "`" . str_replace("`", "``", $v) . "`";
	}
	function
	table($v)
	{
		return
			idf_escape($v);
	}
	function
	connect()
	{
		global $c, $ah, $mg;
		$g = new
			Min_DB;
		$nb = $c->credentials();
		if ($g->connect($nb[0], $nb[1], $nb[2])) {
			$g->set_charset(charset($g));
			$g->query("SET sql_quote_show_create = 1, autocommit = 1");
			if (min_version('5.7.8', 10.2, $g)) {
				$mg[lang(68)][] = "json";
				$ah["json"] = 4294967295;
			}
			return $g;
		}
		$K = $g->error;
		if (function_exists('iconv') && !is_utf8($K) && strlen($If = iconv("windows-1250", "utf-8", $K)) > strlen($K)) $K = $If;
		return $K;
	}
	function
	get_databases($yc)
	{
		$K = get_session("dbs");
		if ($K === null) {
			$I = (min_version(5) ? "SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME" : "SHOW DATABASES");
			$K = ($yc ? slow_query($I) : get_vals($I));
			restart_session();
			set_session("dbs", $K);
			stop_session();
		}
		return $K;
	}
	function
	limit($I, $Z, $_, $ke = 0, $Rf = " ")
	{
		return " $I$Z" . ($_ !== null ? $Rf . "LIMIT $_" . ($ke ? " OFFSET $ke" : "") : "");
	}
	function
	limit1($Q, $I, $Z, $Rf = "\n")
	{
		return
			limit($I, $Z, 1, 0, $Rf);
	}
	function
	db_collation($k, $Xa)
	{
		global $g;
		$K = null;
		$i = $g->result("SHOW CREATE DATABASE " . idf_escape($k), 1);
		if (preg_match('~ COLLATE ([^ ]+)~', $i, $C)) $K = $C[1];
		elseif (preg_match('~ CHARACTER SET ([^ ]+)~', $i, $C)) $K = $Xa[$C[1]][-1];
		return $K;
	}
	function
	engines()
	{
		$K = array();
		foreach (get_rows("SHOW ENGINES") as $L) {
			if (preg_match("~YES|DEFAULT~", $L["Support"])) $K[] = $L["Engine"];
		}
		return $K;
	}
	function
	logged_user()
	{
		global $g;
		return $g->result("SELECT USER()");
	}
	function
	tables_list()
	{
		return
			get_key_vals(min_version(5) ? "SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME" : "SHOW TABLES");
	}
	function
	count_tables($j)
	{
		$K = array();
		foreach ($j
			as $k) $K[$k] = count(get_vals("SHOW TABLES IN " . idf_escape($k)));
		return $K;
	}
	function
	table_status($E = "", $rc = false)
	{
		$K = array();
		foreach (get_rows($rc && min_version(5) ? "SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() " . ($E != "" ? "AND TABLE_NAME = " . q($E) : "ORDER BY Name") : "SHOW TABLE STATUS" . ($E != "" ? " LIKE " . q(addcslashes($E, "%_\\")) : "")) as $L) {
			if ($L["Engine"] == "InnoDB") $L["Comment"] = preg_replace('~(?:(.+); )?InnoDB free: .*~', '\1', $L["Comment"]);
			if (!isset($L["Engine"])) $L["Comment"] = "";
			if ($E != "") return $L;
			$K[$L["Name"]] = $L;
		}
		return $K;
	}
	function
	is_view($R)
	{
		return $R["Engine"] === null;
	}
	function
	fk_support($R)
	{
		return
			preg_match('~InnoDB|IBMDB2I~i', $R["Engine"]) || (preg_match('~NDB~i', $R["Engine"]) && min_version(5.6));
	}
	function
	fields($Q)
	{
		$K = array();
		foreach (get_rows("SHOW FULL COLUMNS FROM " . table($Q)) as $L) {
			preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~', $L["Type"], $C);
			$K[$L["Field"]] = array("field" => $L["Field"], "full_type" => $L["Type"], "type" => $C[1], "length" => $C[2], "unsigned" => ltrim($C[3] . $C[4]), "default" => ($L["Default"] != "" || preg_match("~char|set~", $C[1]) ? (preg_match('~text~', $C[1]) ? stripslashes(preg_replace("~^'(.*)'\$~", '\1', $L["Default"])) : $L["Default"]) : null), "null" => ($L["Null"] == "YES"), "auto_increment" => ($L["Extra"] == "auto_increment"), "on_update" => (preg_match('~^on update (.+)~i', $L["Extra"], $C) ? $C[1] : ""), "collation" => $L["Collation"], "privileges" => array_flip(preg_split('~, *~', $L["Privileges"])), "comment" => $L["Comment"], "primary" => ($L["Key"] == "PRI"), "generated" => preg_match('~^(VIRTUAL|PERSISTENT|STORED)~', $L["Extra"]),);
		}
		return $K;
	}
	function
	indexes($Q, $h = null)
	{
		$K = array();
		foreach (get_rows("SHOW INDEX FROM " . table($Q), $h) as $L) {
			$E = $L["Key_name"];
			$K[$E]["type"] = ($E == "PRIMARY" ? "PRIMARY" : ($L["Index_type"] == "FULLTEXT" ? "FULLTEXT" : ($L["Non_unique"] ? ($L["Index_type"] == "SPATIAL" ? "SPATIAL" : "INDEX") : "UNIQUE")));
			$K[$E]["columns"][] = $L["Column_name"];
			$K[$E]["lengths"][] = ($L["Index_type"] == "SPATIAL" ? null : $L["Sub_part"]);
			$K[$E]["descs"][] = null;
		}
		return $K;
	}
	function
	foreign_keys($Q)
	{
		global $g, $re;
		static $Ue = '(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';
		$K = array();
		$lb = $g->result("SHOW CREATE TABLE " . table($Q), 1);
		if ($lb) {
			preg_match_all("~CONSTRAINT ($Ue) FOREIGN KEY ?\\(((?:$Ue,? ?)+)\\) REFERENCES ($Ue)(?:\\.($Ue))? \\(((?:$Ue,? ?)+)\\)(?: ON DELETE ($re))?(?: ON UPDATE ($re))?~", $lb, $Jd, PREG_SET_ORDER);
			foreach ($Jd
				as $C) {
				preg_match_all("~$Ue~", $C[2], $bg);
				preg_match_all("~$Ue~", $C[5], $Ag);
				$K[idf_unescape($C[1])] = array("db" => idf_unescape($C[4] != "" ? $C[3] : $C[4]), "table" => idf_unescape($C[4] != "" ? $C[4] : $C[3]), "source" => array_map('idf_unescape', $bg[0]), "target" => array_map('idf_unescape', $Ag[0]), "on_delete" => ($C[6] ? $C[6] : "RESTRICT"), "on_update" => ($C[7] ? $C[7] : "RESTRICT"),);
			}
		}
		return $K;
	}
	function
	view($E)
	{
		global $g;
		return
			array("select" => preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU', '', $g->result("SHOW CREATE VIEW " . table($E), 1)));
	}
	function
	collations()
	{
		$K = array();
		foreach (get_rows("SHOW COLLATION") as $L) {
			if ($L["Default"]) $K[$L["Charset"]][-1] = $L["Collation"];
			else $K[$L["Charset"]][] = $L["Collation"];
		}
		ksort($K);
		foreach ($K
			as $z => $X) asort($K[$z]);
		return $K;
	}
	function
	information_schema($k)
	{
		return (min_version(5) && $k == "information_schema") || (min_version(5.5) && $k == "performance_schema");
	}
	function
	error()
	{
		global $g;
		return
			h(preg_replace('~^You have an error.*syntax to use~U', "Syntax error", $g->error));
	}
	function
	create_database($k, $Wa)
	{
		return
			queries("CREATE DATABASE " . idf_escape($k) . ($Wa ? " COLLATE " . q($Wa) : ""));
	}
	function
	drop_databases($j)
	{
		$K = apply_queries("DROP DATABASE", $j, 'idf_escape');
		restart_session();
		set_session("dbs", null);
		return $K;
	}
	function
	rename_database($E, $Wa)
	{
		$K = false;
		if (create_database($E, $Wa)) {
			$S = array();
			$wh = array();
			foreach (tables_list() as $Q => $U) {
				if ($U == 'VIEW') $wh[] = $Q;
				else $S[] = $Q;
			}
			$K = (!$S && !$wh) || move_tables($S, $wh, $E);
			drop_databases($K ? array(DB) : array());
		}
		return $K;
	}
	function
	auto_increment()
	{
		$za = " PRIMARY KEY";
		if ($_GET["create"] != "" && $_POST["auto_increment_col"]) {
			foreach (indexes($_GET["create"]) as $w) {
				if (in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"], $w["columns"], true)) {
					$za = "";
					break;
				}
				if ($w["type"] == "PRIMARY") $za = " UNIQUE";
			}
		}
		return " AUTO_INCREMENT$za";
	}
	function
	alter_table($Q, $E, $o, $_c, $bb, $Zb, $Wa, $ya, $Qe)
	{
		$sa = array();
		foreach ($o
			as $n) $sa[] = ($n[1] ? ($Q != "" ? ($n[0] != "" ? "CHANGE " . idf_escape($n[0]) : "ADD") : " ") . " " . implode($n[1]) . ($Q != "" ? $n[2] : "") : "DROP " . idf_escape($n[0]));
		$sa = array_merge($sa, $_c);
		$ig = ($bb !== null ? " COMMENT=" . q($bb) : "") . ($Zb ? " ENGINE=" . q($Zb) : "") . ($Wa ? " COLLATE " . q($Wa) : "") . ($ya != "" ? " AUTO_INCREMENT=$ya" : "");
		if ($Q == "") return
			queries("CREATE TABLE " . table($E) . " (\n" . implode(",\n", $sa) . "\n)$ig$Qe");
		if ($Q != $E) $sa[] = "RENAME TO " . table($E);
		if ($ig) $sa[] = ltrim($ig);
		return ($sa || $Qe ? queries("ALTER TABLE " . table($Q) . "\n" . implode(",\n", $sa) . $Qe) : true);
	}
	function
	alter_indexes($Q, $sa)
	{
		foreach ($sa
			as $z => $X) $sa[$z] = ($X[2] == "DROP" ? "\nDROP INDEX " . idf_escape($X[1]) : "\nADD $X[0] " . ($X[0] == "PRIMARY" ? "KEY " : "") . ($X[1] != "" ? idf_escape($X[1]) . " " : "") . "(" . implode(", ", $X[2]) . ")");
		return
			queries("ALTER TABLE " . table($Q) . implode(",", $sa));
	}
	function
	truncate_tables($S)
	{
		return
			apply_queries("TRUNCATE TABLE", $S);
	}
	function
	drop_views($wh)
	{
		return
			queries("DROP VIEW " . implode(", ", array_map('table', $wh)));
	}
	function
	drop_tables($S)
	{
		return
			queries("DROP TABLE " . implode(", ", array_map('table', $S)));
	}
	function
	move_tables($S, $wh, $Ag)
	{
		global $g;
		$zf = array();
		foreach ($S
			as $Q) $zf[] = table($Q) . " TO " . idf_escape($Ag) . "." . table($Q);
		if (!$zf || queries("RENAME TABLE " . implode(", ", $zf))) {
			$Bb = array();
			foreach ($wh
				as $Q) $Bb[table($Q)] = view($Q);
			$g->select_db($Ag);
			$k = idf_escape(DB);
			foreach ($Bb
				as $E => $vh) {
				if (!queries("CREATE VIEW $E AS " . str_replace(" $k.", " ", $vh["select"])) || !queries("DROP VIEW $k.$E")) return
					false;
			}
			return
				true;
		}
		return
			false;
	}
	function
	copy_tables($S, $wh, $Ag)
	{
		queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");
		foreach ($S
			as $Q) {
			$E = ($Ag == DB ? table("copy_$Q") : idf_escape($Ag) . "." . table($Q));
			if (($_POST["overwrite"] && !queries("\nDROP TABLE IF EXISTS $E")) || !queries("CREATE TABLE $E LIKE " . table($Q)) || !queries("INSERT INTO $E SELECT * FROM " . table($Q))) return
				false;
			foreach (get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\"))) as $L) {
				$Vg = $L["Trigger"];
				if (!queries("CREATE TRIGGER " . ($Ag == DB ? idf_escape("copy_$Vg") : idf_escape($Ag) . "." . idf_escape($Vg)) . " $L[Timing] $L[Event] ON $E FOR EACH ROW\n$L[Statement];")) return
					false;
			}
		}
		foreach ($wh
			as $Q) {
			$E = ($Ag == DB ? table("copy_$Q") : idf_escape($Ag) . "." . table($Q));
			$vh = view($Q);
			if (($_POST["overwrite"] && !queries("DROP VIEW IF EXISTS $E")) || !queries("CREATE VIEW $E AS $vh[select]")) return
				false;
		}
		return
			true;
	}
	function
	trigger($E)
	{
		if ($E == "") return
			array();
		$M = get_rows("SHOW TRIGGERS WHERE `Trigger` = " . q($E));
		return
			reset($M);
	}
	function
	triggers($Q)
	{
		$K = array();
		foreach (get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\"))) as $L) $K[$L["Trigger"]] = array($L["Timing"], $L["Event"]);
		return $K;
	}
	function
	trigger_options()
	{
		return
			array("Timing" => array("BEFORE", "AFTER"), "Event" => array("INSERT", "UPDATE", "DELETE"), "Type" => array("FOR EACH ROW"),);
	}
	function
	routine($E, $U)
	{
		global $g, $bc, $dd, $ah;
		$qa = array("bool", "boolean", "integer", "double precision", "real", "dec", "numeric", "fixed", "national char", "national varchar");
		$cg = "(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
		$Zg = "((" . implode("|", array_merge(array_keys($ah), $qa)) . ")\\b(?:\\s*\\(((?:[^'\")]|$bc)++)\\))?\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?";
		$Ue = "$cg*(" . ($U == "FUNCTION" ? "" : $dd) . ")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Zg";
		$i = $g->result("SHOW CREATE $U " . idf_escape($E), 2);
		preg_match("~\\(((?:$Ue\\s*,?)*)\\)\\s*" . ($U == "FUNCTION" ? "RETURNS\\s+$Zg\\s+" : "") . "(.*)~is", $i, $C);
		$o = array();
		preg_match_all("~$Ue\\s*,?~is", $C[1], $Jd, PREG_SET_ORDER);
		foreach ($Jd
			as $Le) $o[] = array("field" => str_replace("``", "`", $Le[2]) . $Le[3], "type" => strtolower($Le[5]), "length" => preg_replace_callback("~$bc~s", 'normalize_enum', $Le[6]), "unsigned" => strtolower(preg_replace('~\s+~', ' ', trim("$Le[8] $Le[7]"))), "null" => 1, "full_type" => $Le[4], "inout" => strtoupper($Le[1]), "collation" => strtolower($Le[9]),);
		if ($U != "FUNCTION") return
			array("fields" => $o, "definition" => $C[11]);
		return
			array("fields" => $o, "returns" => array("type" => $C[12], "length" => $C[13], "unsigned" => $C[15], "collation" => $C[16]), "definition" => $C[17], "language" => "SQL",);
	}
	function
	routines()
	{
		return
			get_rows("SELECT ROUTINE_NAME AS SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = " . q(DB));
	}
	function
	routine_languages()
	{
		return
			array();
	}
	function
	routine_id($E, $L)
	{
		return
			idf_escape($E);
	}
	function
	last_id()
	{
		global $g;
		return $g->result("SELECT LAST_INSERT_ID()");
	}
	function
	explain($g, $I)
	{
		return $g->query("EXPLAIN " . (min_version(5.1) && !min_version(5.7) ? "PARTITIONS " : "") . $I);
	}
	function
	found_rows($R, $Z)
	{
		return ($Z || $R["Engine"] != "InnoDB" ? null : $R["Rows"]);
	}
	function
	types()
	{
		return
			array();
	}
	function
	schemas()
	{
		return
			array();
	}
	function
	get_schema()
	{
		return "";
	}
	function
	set_schema($Kf, $h = null)
	{
		return
			true;
	}
	function
	create_sql($Q, $ya, $ng)
	{
		global $g;
		$K = $g->result("SHOW CREATE TABLE " . table($Q), 1);
		if (!$ya) $K = preg_replace('~ AUTO_INCREMENT=\d+~', '', $K);
		return $K;
	}
	function
	truncate_sql($Q)
	{
		return "TRUNCATE " . table($Q);
	}
	function
	use_sql($ub)
	{
		return "USE " . idf_escape($ub);
	}
	function
	trigger_sql($Q)
	{
		$K = "";
		foreach (get_rows("SHOW TRIGGERS LIKE " . q(addcslashes($Q, "%_\\")), null, "-- ") as $L) $K .= "\nCREATE TRIGGER " . idf_escape($L["Trigger"]) . " $L[Timing] $L[Event] ON " . table($L["Table"]) . " FOR EACH ROW\n$L[Statement];;\n";
		return $K;
	}
	function
	show_variables()
	{
		return
			get_key_vals("SHOW VARIABLES");
	}
	function
	process_list()
	{
		return
			get_rows("SHOW FULL PROCESSLIST");
	}
	function
	show_status()
	{
		return
			get_key_vals("SHOW STATUS");
	}
	function
	convert_field($n)
	{
		if (preg_match("~binary~", $n["type"])) return "HEX(" . idf_escape($n["field"]) . ")";
		if ($n["type"] == "bit") return "BIN(" . idf_escape($n["field"]) . " + 0)";
		if (preg_match("~geometry|point|linestring|polygon~", $n["type"])) return (min_version(8) ? "ST_" : "") . "AsWKT(" . idf_escape($n["field"]) . ")";
	}
	function
	unconvert_field($n, $K)
	{
		if (preg_match("~binary~", $n["type"])) $K = "UNHEX($K)";
		if ($n["type"] == "bit") $K = "CONV($K, 2, 10) + 0";
		if (preg_match("~geometry|point|linestring|polygon~", $n["type"])) $K = (min_version(8) ? "ST_" : "") . "GeomFromText($K, SRID($n[field]))";
		return $K;
	}
	function
	support($sc)
	{
		return !preg_match("~scheme|sequence|type|view_trigger|materializedview" . (min_version(8) ? "" : "|descidx" . (min_version(5.1) ? "" : "|event|partitioning" . (min_version(5) ? "" : "|routine|trigger|view"))) . "~", $sc);
	}
	function
	kill_process($X)
	{
		return
			queries("KILL " . number($X));
	}
	function
	connection_id()
	{
		return "SELECT CONNECTION_ID()";
	}
	function
	max_connections()
	{
		global $g;
		return $g->result("SELECT @@max_connections");
	}
	function
	driver_config()
	{
		$ah = array();
		$mg = array();
		foreach (array(lang(69) => array("tinyint" => 3, "smallint" => 5, "mediumint" => 8, "int" => 10, "bigint" => 20, "decimal" => 66, "float" => 12, "double" => 21), lang(70) => array("date" => 10, "datetime" => 19, "timestamp" => 19, "time" => 10, "year" => 4), lang(68) => array("char" => 255, "varchar" => 65535, "tinytext" => 255, "text" => 65535, "mediumtext" => 16777215, "longtext" => 4294967295), lang(71) => array("enum" => 65535, "set" => 64), lang(72) => array("bit" => 20, "binary" => 255, "varbinary" => 65535, "tinyblob" => 255, "blob" => 65535, "mediumblob" => 16777215, "longblob" => 4294967295), lang(73) => array("geometry" => 0, "point" => 0, "linestring" => 0, "polygon" => 0, "multipoint" => 0, "multilinestring" => 0, "multipolygon" => 0, "geometrycollection" => 0),) as $z => $X) {
			$ah += $X;
			$mg[$z] = array_keys($X);
		}
		return
			array('possible_drivers' => array("MySQLi", "MySQL", "PDO_MySQL"), 'jush' => "sql", 'types' => $ah, 'structured_types' => $mg, 'unsigned' => array("unsigned", "zerofill", "unsigned zerofill"), 'operators' => array("=", "<", ">", "<=", ">=", "!=", "LIKE", "LIKE %%", "REGEXP", "IN", "FIND_IN_SET", "IS NULL", "NOT LIKE", "NOT REGEXP", "NOT IN", "IS NOT NULL", "SQL"), 'functions' => array("char_length", "date", "from_unixtime", "lower", "round", "floor", "ceil", "sec_to_time", "time_to_sec", "upper"), 'grouping' => array("avg", "count", "count distinct", "group_concat", "max", "min", "sum"), 'edit_functions' => array(array("char" => "md5/sha1/password/encrypt/uuid", "binary" => "md5/sha1", "date|time" => "now",), array(number_type() => "+/-", "date" => "+ interval/- interval", "time" => "addtime/subtime", "char|text" => "concat",)),);
	}
}
$eb = driver_config();
$cf = $eb['possible_drivers'];
$y = $eb['jush'];
$ah = $eb['types'];
$mg = $eb['structured_types'];
$hh = $eb['unsigned'];
$ve = $eb['operators'];
$Gc = $eb['functions'];
$Lc = $eb['grouping'];
$Rb = $eb['edit_functions'];
if ($c->operators === null) $c->operators = $ve;
define("SERVER", $_GET[DRIVER]);
define("DB", $_GET["db"]);
define("ME", preg_replace('~\?.*~', '', relative_uri()) . '?' . (sid() ? SID . '&' : '') . (SERVER !== null ? DRIVER . "=" . urlencode(SERVER) . '&' : '') . (isset($_GET["username"]) ? "username=" . urlencode($_GET["username"]) . '&' : '') . (DB != "" ? 'db=' . urlencode(DB) . '&' . (isset($_GET["ns"]) ? "ns=" . urlencode($_GET["ns"]) . "&" : "") : ''));
$fa = "4.8.1";
function
page_header($Jg, $m = "", $Ha = array(), $Kg = "")
{
	global $a, $fa, $c, $Kb, $y;
	page_headers();
	if (is_ajax() && $m) {
		page_messages($m);
		exit;
	}
	$Lg = $Jg . ($Kg != "" ? ": $Kg" : "");
	$Mg = strip_tags($Lg . (SERVER != "" && SERVER != "localhost" ? h(" - " . SERVER) : "") . " - " . $c->name());
	echo '<!DOCTYPE html>
<html lang="', $a, '" dir="', lang(74), '">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<title>', $Mg, '</title>
<link rel="stylesheet" type="text/css" href="', h(preg_replace("~\\?.*~", "", ME) . "?file=default.css&version=4.8.1"), '">
', script_src(preg_replace("~\\?.*~", "", ME) . "?file=functions.js&version=4.8.1");
	if ($c->head()) {
		echo '<link rel="shortcut icon" type="image/x-icon" href="', h(preg_replace("~\\?.*~", "", ME) . "?file=favicon.ico&version=4.8.1"), '">
<link rel="apple-touch-icon" href="', h(preg_replace("~\\?.*~", "", ME) . "?file=favicon.ico&version=4.8.1"), '">
';
		foreach ($c->css() as $pb) {
			echo '<link rel="stylesheet" type="text/css" href="', h($pb), '">
';
		}
	}
	echo '
<body class="', lang(74), ' nojs">
';
	$vc = get_temp_dir() . "/adminer.version";
	if (!$_COOKIE["adminer_version"] && function_exists('openssl_verify') && file_exists($vc) && filemtime($vc) + 86400 > time()) {
		$uh = unserialize(file_get_contents($vc));
		$mf = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";
		if (openssl_verify($uh["version"], base64_decode($uh["signature"]), $mf) == 1) $_COOKIE["adminer_version"] = $uh["version"];
	}
	echo '<script', nonce(), '>
mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick', (isset($_COOKIE["adminer_version"]) ? "" : ", onload: partial(verifyVersion, '$fa', '" . js_escape(ME) . "', '" . get_token() . "')"); ?>});
document.body.className = document.body.className.replace(/ nojs/, ' js');
var offlineMessage = '<?php echo
						js_escape(lang(75)), '\';
var thousandsSeparator = \'', js_escape(lang(5)), '\';
</script>

<div id="help" class="jush-', $y, ' jsonly hidden"></div>
', script("mixin(qs('#help'), {onmouseover: function () { helpOpen = 1; }, onmouseout: helpMouseout});"), '
<div id="content">
';
						if ($Ha !== null) {
							$A = substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1);
							echo '<p id="breadcrumb"><a href="' . h($A ? $A : ".") . '">' . $Kb[DRIVER] . '</a> &raquo; ';
							$A = substr(preg_replace('~\b(db|ns)=[^&]*&~', '', ME), 0, -1);
							$O = $c->serverName(SERVER);
							$O = ($O != "" ? $O : lang(23));
							if ($Ha === false) echo "$O\n";
							else {
								echo "<a href='" . h($A) . "' accesskey='1' title='Alt+Shift+1'>$O</a> &raquo; ";
								if ($_GET["ns"] != "" || (DB != "" && is_array($Ha))) echo '<a href="' . h($A . "&db=" . urlencode(DB) . (support("scheme") ? "&ns=" : "")) . '">' . h(DB) . '</a> &raquo; ';
								if (is_array($Ha)) {
									if ($_GET["ns"] != "") echo '<a href="' . h(substr(ME, 0, -1)) . '">' . h($_GET["ns"]) . '</a> &raquo; ';
									foreach ($Ha
										as $z => $X) {
										$Db = (is_array($X) ? $X[1] : h($X));
										if ($Db != "") echo "<a href='" . h(ME . "$z=") . urlencode(is_array($X) ? $X[0] : $X) . "'>$Db</a> &raquo; ";
									}
								}
								echo "$Jg\n";
							}
						}
						echo "<h2>$Lg</h2>\n", "<div id='ajaxstatus' class='jsonly hidden'></div>\n";
						restart_session();
						page_messages($m);
						$j = &get_session("dbs");
						if (DB != "" && $j && !in_array(DB, $j, true)) $j = null;
						stop_session();
						define("PAGE_HEADER", 1);
					}
					function
					page_headers()
					{
						global $c;
						header("Content-Type: text/html; charset=utf-8");
						header("Cache-Control: no-cache");
						header("X-Frame-Options: deny");
						header("X-XSS-Protection: 0");
						header("X-Content-Type-Options: nosniff");
						header("Referrer-Policy: origin-when-cross-origin");
						foreach ($c->csp() as $ob) {
							$Rc = array();
							foreach ($ob
								as $z => $X) $Rc[] = "$z $X";
							header("Content-Security-Policy: " . implode("; ", $Rc));
						}
						$c->headers();
					}
					function
					csp()
					{
						return
							array(array("script-src" => "'self' 'unsafe-inline' 'nonce-" . get_nonce() . "' 'strict-dynamic'", "connect-src" => "'self'", "frame-src" => "https://www.adminer.org", "object-src" => "'none'", "base-uri" => "'none'", "form-action" => "'self'",),);
					}
					function
					get_nonce()
					{
						static $fe;
						if (!$fe) $fe = base64_encode(rand_string());
						return $fe;
					}
					function
					page_messages($m)
					{
						$jh = preg_replace('~^[^?]*~', '', $_SERVER["REQUEST_URI"]);
						$Ud = $_SESSION["messages"][$jh];
						if ($Ud) {
							echo "<div class='message'>" . implode("</div>\n<div class='message'>", $Ud) . "</div>" . script("messagesPrint();");
							unset($_SESSION["messages"][$jh]);
						}
						if ($m) echo "<div class='error'>$m</div>\n";
					}
					function
					page_footer($Wd = "")
					{
						global $c, $T;
						echo '</div>

';
						switch_lang();
						if ($Wd != "auth") {
							echo '<form action="" method="post">
<p class="logout">
<input type="submit" name="logout" value="', lang(76), '" id="logout">
<input type="hidden" name="token" value="', $T, '">
</p>
</form>
';
						}
						echo '<div id="menu">
';
						$c->navigation($Wd);
						echo '</div>
', script("setupSubmitHighlight(document);");
					}
					function
					int32($Zd)
					{
						while ($Zd >= 2147483648) $Zd -= 4294967296;
						while ($Zd <= -2147483649) $Zd += 4294967296;
						return (int)$Zd;
					}
					function
					long2str($W, $yh)
					{
						$If = '';
						foreach ($W
							as $X) $If .= pack('V', $X);
						if ($yh) return
							substr($If, 0, end($W));
						return $If;
					}
					function
					str2long($If, $yh)
					{
						$W = array_values(unpack('V*', str_pad($If, 4 * ceil(strlen($If) / 4), "\0")));
						if ($yh) $W[] = strlen($If);
						return $W;
					}
					function
					xxtea_mx($Eh, $Dh, $qg, $od)
					{
						return
							int32((($Eh >> 5 & 0x7FFFFFF) ^ $Dh << 2) + (($Dh >> 3 & 0x1FFFFFFF) ^ $Eh << 4)) ^ int32(($qg ^ $Dh) + ($od ^ $Eh));
					}
					function
					encrypt_string($kg, $z)
					{
						if ($kg == "") return "";
						$z = array_values(unpack("V*", pack("H*", md5($z))));
						$W = str2long($kg, true);
						$Zd = count($W) - 1;
						$Eh = $W[$Zd];
						$Dh = $W[0];
						$H = floor(6 + 52 / ($Zd + 1));
						$qg = 0;
						while ($H-- > 0) {
							$qg = int32($qg + 0x9E3779B9);
							$Qb = $qg >> 2 & 3;
							for ($Je = 0; $Je < $Zd; $Je++) {
								$Dh = $W[$Je + 1];
								$Yd = xxtea_mx($Eh, $Dh, $qg, $z[$Je & 3 ^ $Qb]);
								$Eh = int32($W[$Je] + $Yd);
								$W[$Je] = $Eh;
							}
							$Dh = $W[0];
							$Yd = xxtea_mx($Eh, $Dh, $qg, $z[$Je & 3 ^ $Qb]);
							$Eh = int32($W[$Zd] + $Yd);
							$W[$Zd] = $Eh;
						}
						return
							long2str($W, false);
					}
					function
					decrypt_string($kg, $z)
					{
						if ($kg == "") return "";
						if (!$z) return
							false;
						$z = array_values(unpack("V*", pack("H*", md5($z))));
						$W = str2long($kg, false);
						$Zd = count($W) - 1;
						$Eh = $W[$Zd];
						$Dh = $W[0];
						$H = floor(6 + 52 / ($Zd + 1));
						$qg = int32($H * 0x9E3779B9);
						while ($qg) {
							$Qb = $qg >> 2 & 3;
							for ($Je = $Zd; $Je > 0; $Je--) {
								$Eh = $W[$Je - 1];
								$Yd = xxtea_mx($Eh, $Dh, $qg, $z[$Je & 3 ^ $Qb]);
								$Dh = int32($W[$Je] - $Yd);
								$W[$Je] = $Dh;
							}
							$Eh = $W[$Zd];
							$Yd = xxtea_mx($Eh, $Dh, $qg, $z[$Je & 3 ^ $Qb]);
							$Dh = int32($W[0] - $Yd);
							$W[0] = $Dh;
							$qg = int32($qg - 0x9E3779B9);
						}
						return
							long2str($W, true);
					}
					$g = '';
					$Qc = $_SESSION["token"];
					if (!$Qc) $_SESSION["token"] = rand(1, 1e6);
					$T = get_token();
					$We = array();
					if ($_COOKIE["adminer_permanent"]) {
						foreach (explode(" ", $_COOKIE["adminer_permanent"]) as $X) {
							list($z) = explode(":", $X);
							$We[$z] = $X;
						}
					}
					function
					add_invalid_login()
					{
						global $c;
						$q = file_open_lock(get_temp_dir() . "/adminer.invalid");
						if (!$q) return;
						$id = unserialize(stream_get_contents($q));
						$Gg = time();
						if ($id) {
							foreach ($id
								as $jd => $X) {
								if ($X[0] < $Gg) unset($id[$jd]);
							}
						}
						$hd = &$id[$c->bruteForceKey()];
						if (!$hd) $hd = array($Gg + 30 * 60, 0);
						$hd[1]++;
						file_write_unlock($q, serialize($id));
					}
					function
					check_invalid_login()
					{
						global $c;
						$id = unserialize(@file_get_contents(get_temp_dir() . "/adminer.invalid"));
						$hd = ($id ? $id[$c->bruteForceKey()] : array());
						$ee = ($hd[1] > 29 ? $hd[0] - time() : 0);
						if ($ee > 0) auth_error(lang(77, ceil($ee / 60)));
					}
					$xa = $_POST["auth"];
					if ($xa) {
						session_regenerate_id();
						$th = $xa["driver"];
						$O = $xa["server"];
						$V = $xa["username"];
						$G = (string)$xa["password"];
						$k = $xa["db"];
						set_password($th, $O, $V, $G);
						$_SESSION["db"][$th][$O][$V][$k] = true;
						if ($xa["permanent"]) {
							$z = base64_encode($th) . "-" . base64_encode($O) . "-" . base64_encode($V) . "-" . base64_encode($k);
							$hf = $c->permanentLogin(true);
							$We[$z] = "$z:" . base64_encode($hf ? encrypt_string($G, $hf) : "");
							cookie("adminer_permanent", implode(" ", $We));
						}
						if (count($_POST) == 1 || DRIVER != $th || SERVER != $O || $_GET["username"] !== $V || DB != $k) redirect(auth_url($th, $O, $V, $k));
					} elseif ($_POST["logout"] && (!$Qc || verify_token())) {
						foreach (array("pwds", "db", "dbs", "queries") as $z) set_session($z, null);
						unset_permanent();
						redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~', '', ME), 0, -1), lang(78) . ' ' . lang(79));
					} elseif ($We && !$_SESSION["pwds"]) {
						session_regenerate_id();
						$hf = $c->permanentLogin();
						foreach ($We
							as $z => $X) {
							list(, $Qa) = explode(":", $X);
							list($th, $O, $V, $k) = array_map('base64_decode', explode("-", $z));
							set_password($th, $O, $V, decrypt_string(base64_decode($Qa), $hf));
							$_SESSION["db"][$th][$O][$V][$k] = true;
						}
					}
					function
					unset_permanent()
					{
						global $We;
						foreach ($We
							as $z => $X) {
							list($th, $O, $V, $k) = array_map('base64_decode', explode("-", $z));
							if ($th == DRIVER && $O == SERVER && $V == $_GET["username"] && $k == DB) unset($We[$z]);
						}
						cookie("adminer_permanent", implode(" ", $We));
					}
					function
					auth_error($m)
					{
						global $c, $Qc;
						$Uf = session_name();
						if (isset($_GET["username"])) {
							header("HTTP/1.1 403 Forbidden");
							if (($_COOKIE[$Uf] || $_GET[$Uf]) && !$Qc) $m = lang(80);
							else {
								restart_session();
								add_invalid_login();
								$G = get_password();
								if ($G !== null) {
									if ($G === false) $m .= ($m ? '<br>' : '') . lang(81, target_blank(), '<code>permanentLogin()</code>');
									set_password(DRIVER, SERVER, $_GET["username"], null);
								}
								unset_permanent();
							}
						}
						if (!$_COOKIE[$Uf] && $_GET[$Uf] && ini_bool("session.use_only_cookies")) $m = lang(82);
						$Me = session_get_cookie_params();
						cookie("adminer_key", ($_COOKIE["adminer_key"] ? $_COOKIE["adminer_key"] : rand_string()), $Me["lifetime"]);
						page_header(lang(27), $m, null);
						echo "<form action='' method='post'>\n", "<div>";
						if (hidden_fields($_POST, array("auth"))) echo "<p class='message'>" . lang(83) . "\n";
						echo "</div>\n";
						$c->loginForm();
						echo "</form>\n";
						page_footer("auth");
						exit;
					}
					if (isset($_GET["username"]) && !class_exists("Min_DB")) {
						unset($_SESSION["pwds"][DRIVER]);
						unset_permanent();
						page_header(lang(84), lang(85, implode(", ", $cf)), false);
						page_footer("auth");
						exit;
					}
					stop_session(true);
					if (isset($_GET["username"]) && is_string(get_password())) {
						list($Vc, $Ye) = explode(":", SERVER, 2);
						if (preg_match('~^\s*([-+]?\d+)~', $Ye, $C) && ($C[1] < 1024 || $C[1] > 65535)) auth_error(lang(86));
						check_invalid_login();
						$g = connect();
						$l = new
							Min_Driver($g);
					}
					$Fd = null;
					if (!is_object($g) || ($Fd = $c->login($_GET["username"], get_password())) !== true) {
						$m = (is_string($g) ? h($g) : (is_string($Fd) ? $Fd : lang(87)));
						auth_error($m . (preg_match('~^ | $~', get_password()) ? '<br>' . lang(88) : ''));
					}
					if ($_POST["logout"] && $Qc && !verify_token()) {
						page_header(lang(76), lang(89));
						page_footer("db");
						exit;
					}
					if ($xa && $_POST["token"]) $_POST["token"] = $T;
					$m = '';
					if ($_POST) {
						if (!verify_token()) {
							$cd = "max_input_vars";
							$Pd = ini_get($cd);
							if (extension_loaded("suhosin")) {
								foreach (array("suhosin.request.max_vars", "suhosin.post.max_vars") as $z) {
									$X = ini_get($z);
									if ($X && (!$Pd || $X < $Pd)) {
										$cd = $z;
										$Pd = $X;
									}
								}
							}
							$m = (!$_POST["token"] && $Pd ? lang(90, "'$cd'") : lang(89) . ' ' . lang(91));
						}
					} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
						$m = lang(92, "'post_max_size'");
						if (isset($_GET["sql"])) $m .= ' ' . lang(93);
					}
					function
					select($J, $h = null, $Be = array(), $_ = 0)
					{
						global $y;
						$Ed = array();
						$x = array();
						$e = array();
						$Fa = array();
						$ah = array();
						$K = array();
						odd('');
						for ($t = 0; (!$_ || $t < $_) && ($L = $J->fetch_row()); $t++) {
							if (!$t) {
								echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap'>\n", "<thead><tr>";
								for ($nd = 0; $nd < count($L); $nd++) {
									$n = $J->fetch_field();
									$E = $n->name;
									$Ae = $n->orgtable;
									$_e = $n->orgname;
									$K[$n->table] = $Ae;
									if ($Be && $y == "sql") $Ed[$nd] = ($E == "table" ? "table=" : ($E == "possible_keys" ? "indexes=" : null));
									elseif ($Ae != "") {
										if (!isset($x[$Ae])) {
											$x[$Ae] = array();
											foreach (indexes($Ae, $h) as $w) {
												if ($w["type"] == "PRIMARY") {
													$x[$Ae] = array_flip($w["columns"]);
													break;
												}
											}
											$e[$Ae] = $x[$Ae];
										}
										if (isset($e[$Ae][$_e])) {
											unset($e[$Ae][$_e]);
											$x[$Ae][$_e] = $nd;
											$Ed[$nd] = $Ae;
										}
									}
									if ($n->charsetnr == 63) $Fa[$nd] = true;
									$ah[$nd] = $n->type;
									echo "<th" . ($Ae != "" || $n->name != $_e ? " title='" . h(($Ae != "" ? "$Ae." : "") . $_e) . "'" : "") . ">" . h($E) . ($Be ? doc_link(array('sql' => "explain-output.html#explain_" . strtolower($E), 'mariadb' => "explain/#the-columns-in-explain-select",)) : "");
								}
								echo "</thead>\n";
							}
							echo "<tr" . odd() . ">";
							foreach ($L
								as $z => $X) {
								$A = "";
								if (isset($Ed[$z]) && !$e[$Ed[$z]]) {
									if ($Be && $y == "sql") {
										$Q = $L[array_search("table=", $Ed)];
										$A = ME . $Ed[$z] . urlencode($Be[$Q] != "" ? $Be[$Q] : $Q);
									} else {
										$A = ME . "edit=" . urlencode($Ed[$z]);
										foreach ($x[$Ed[$z]] as $Ua => $nd) $A .= "&where" . urlencode("[" . bracket_escape($Ua) . "]") . "=" . urlencode($L[$nd]);
									}
								} elseif (is_url($X)) $A = $X;
								if ($X === null) $X = "<i>NULL</i>";
								elseif ($Fa[$z] && !is_utf8($X)) $X = "<i>" . lang(36, strlen($X)) . "</i>";
								else {
									$X = h($X);
									if ($ah[$z] == 254) $X = "<code>$X</code>";
								}
								if ($A) $X = "<a href='" . h($A) . "'" . (is_url($A) ? target_blank() : '') . ">$X</a>";
								echo "<td>$X";
							}
						}
						echo ($t ? "</table>\n</div>" : "<p class='message'>" . lang(12)) . "\n";
						return $K;
					}
					function
					referencable_primary($Pf)
					{
						$K = array();
						foreach (table_status('', true) as $ug => $Q) {
							if ($ug != $Pf && fk_support($Q)) {
								foreach (fields($ug) as $n) {
									if ($n["primary"]) {
										if ($K[$ug]) {
											unset($K[$ug]);
											break;
										}
										$K[$ug] = $n;
									}
								}
							}
						}
						return $K;
					}
					function
					adminer_settings()
					{
						parse_str($_COOKIE["adminer_settings"], $Wf);
						return $Wf;
					}
					function
					adminer_setting($z)
					{
						$Wf = adminer_settings();
						return $Wf[$z];
					}
					function
					set_adminer_settings($Wf)
					{
						return
							cookie("adminer_settings", http_build_query($Wf + adminer_settings()));
					}
					function
					textarea($E, $Y, $M = 10, $Ya = 80)
					{
						global $y;
						echo "<textarea name='$E' rows='$M' cols='$Ya' class='sqlarea jush-$y' spellcheck='false' wrap='off'>";
						if (is_array($Y)) {
							foreach ($Y
								as $X) echo
							h($X[0]) . "\n\n\n";
						} else
							echo
							h($Y);
						echo "</textarea>";
					}
					function
					edit_type($z, $n, $Xa, $Bc = array(), $pc = array())
					{
						global $mg, $ah, $hh, $re;
						$U = $n["type"];
						echo '<td><select name="', h($z), '[type]" class="type" aria-labelledby="label-type">';
						if ($U && !isset($ah[$U]) && !isset($Bc[$U]) && !in_array($U, $pc)) $pc[] = $U;
						if ($Bc) $mg[lang(94)] = $Bc;
						echo
						optionlist(array_merge($pc, $mg), $U), '</select><td><input name="', h($z), '[length]" value="', h($n["length"]), '" size="3"', (!$n["length"] && preg_match('~var(char|binary)$~', $U) ? " class='required'" : "");
						echo ' aria-labelledby="label-length"><td class="options">', "<select name='" . h($z) . "[collation]'" . (preg_match('~(char|text|enum|set)$~', $U) ? "" : " class='hidden'") . '><option value="">(' . lang(95) . ')' . optionlist($Xa, $n["collation"]) . '</select>', ($hh ? "<select name='" . h($z) . "[unsigned]'" . (!$U || preg_match(number_type(), $U) ? "" : " class='hidden'") . '><option>' . optionlist($hh, $n["unsigned"]) . '</select>' : ''), (isset($n['on_update']) ? "<select name='" . h($z) . "[on_update]'" . (preg_match('~timestamp|datetime~', $U) ? "" : " class='hidden'") . '>' . optionlist(array("" => "(" . lang(96) . ")", "CURRENT_TIMESTAMP"), (preg_match('~^CURRENT_TIMESTAMP~i', $n["on_update"]) ? "CURRENT_TIMESTAMP" : $n["on_update"])) . '</select>' : ''), ($Bc ? "<select name='" . h($z) . "[on_delete]'" . (preg_match("~`~", $U) ? "" : " class='hidden'") . "><option value=''>(" . lang(97) . ")" . optionlist(explode("|", $re), $n["on_delete"]) . "</select> " : " ");
					}
					function
					process_length($Bd)
					{
						global $bc;
						return (preg_match("~^\\s*\\(?\\s*$bc(?:\\s*,\\s*$bc)*+\\s*\\)?\\s*\$~", $Bd) && preg_match_all("~$bc~", $Bd, $Jd) ? "(" . implode(",", $Jd[0]) . ")" : preg_replace('~^[0-9].*~', '(\0)', preg_replace('~[^-0-9,+()[\]]~', '', $Bd)));
					}
					function
					process_type($n, $Va = "COLLATE")
					{
						global $hh;
						return " $n[type]" . process_length($n["length"]) . (preg_match(number_type(), $n["type"]) && in_array($n["unsigned"], $hh) ? " $n[unsigned]" : "") . (preg_match('~char|text|enum|set~', $n["type"]) && $n["collation"] ? " $Va " . q($n["collation"]) : "");
					}
					function
					process_field($n, $Yg)
					{
						return
							array(idf_escape(trim($n["field"])), process_type($Yg), ($n["null"] ? " NULL" : " NOT NULL"), default_value($n), (preg_match('~timestamp|datetime~', $n["type"]) && $n["on_update"] ? " ON UPDATE $n[on_update]" : ""), (support("comment") && $n["comment"] != "" ? " COMMENT " . q($n["comment"]) : ""), ($n["auto_increment"] ? auto_increment() : null),);
					}
					function
					default_value($n)
					{
						$zb = $n["default"];
						return ($zb === null ? "" : " DEFAULT " . (preg_match('~char|binary|text|enum|set~', $n["type"]) || preg_match('~^(?![a-z])~i', $zb) ? q($zb) : $zb));
					}
					function
					type_class($U)
					{
						foreach (array('char' => 'text', 'date' => 'time|year', 'binary' => 'blob', 'enum' => 'set',) as $z => $X) {
							if (preg_match("~$z|$X~", $U)) return " class='$z'";
						}
					}
					function
					edit_fields($o, $Xa, $U = "TABLE", $Bc = array())
					{
						global $dd;
						$o = array_values($o);
						$_b = (($_POST ? $_POST["defaults"] : adminer_setting("defaults")) ? "" : " class='hidden'");
						$cb = (($_POST ? $_POST["comments"] : adminer_setting("comments")) ? "" : " class='hidden'");
						echo '<thead><tr>
';
						if ($U == "PROCEDURE") {
							echo '<td>';
						}
						echo '<th id="label-name">', ($U == "TABLE" ? lang(98) : lang(99)), '<td id="label-type">', lang(38), '<textarea id="enum-edit" rows="4" cols="12" wrap="off" style="display: none;"></textarea>', script("qs('#enum-edit').onblur = editingLengthBlur;"), '<td id="label-length">', lang(100), '<td>', lang(101);
						if ($U == "TABLE") {
							echo '<td id="label-null">NULL
<td><input type="radio" name="auto_increment_col" value=""><acronym id="label-ai" title="', lang(40), '">AI</acronym>', doc_link(array('sql' => "example-auto-increment.html", 'mariadb' => "auto_increment/",)), '<td id="label-default"', $_b, '>', lang(41), (support("comment") ? "<td id='label-comment'$cb>" . lang(39) : "");
						}
						echo '<td>', "<input type='image' class='icon' name='add[" . (support("move_col") ? 0 : count($o)) . "]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.8.1") . "' alt='+' title='" . lang(102) . "'>" . script("row_count = " . count($o) . ";"), '</thead>
<tbody>
', script("mixin(qsl('tbody'), {onclick: editingClick, onkeydown: editingKeydown, oninput: editingInput});");
						foreach ($o
							as $t => $n) {
							$t++;
							$Ce = $n[($_POST ? "orig" : "field")];
							$Hb = (isset($_POST["add"][$t - 1]) || (isset($n["field"]) && !$_POST["drop_col"][$t])) && (support("drop_col") || $Ce == "");
							echo '<tr', ($Hb ? "" : " style='display: none;'"), '>
', ($U == "PROCEDURE" ? "<td>" . html_select("fields[$t][inout]", explode("|", $dd), $n["inout"]) : ""), '<th>';
							if ($Hb) {
								echo '<input name="fields[', $t, '][field]" value="', h($n["field"]), '" data-maxlength="64" autocapitalize="off" aria-labelledby="label-name">';
							}
							echo '<input type="hidden" name="fields[', $t, '][orig]" value="', h($Ce), '">';
							edit_type("fields[$t]", $n, $Xa, $Bc);
							if ($U == "TABLE") {
								echo '<td>', checkbox("fields[$t][null]", 1, $n["null"], "", "", "block", "label-null"), '<td><label class="block"><input type="radio" name="auto_increment_col" value="', $t, '"';
								if ($n["auto_increment"]) {
									echo ' checked';
								}
								echo ' aria-labelledby="label-ai"></label><td', $_b, '>', checkbox("fields[$t][has_default]", 1, $n["has_default"], "", "", "", "label-default"), '<input name="fields[', $t, '][default]" value="', h($n["default"]), '" aria-labelledby="label-default">', (support("comment") ? "<td$cb><input name='fields[$t][comment]' value='" . h($n["comment"]) . "' data-maxlength='" . (min_version(5.5) ? 1024 : 255) . "' aria-labelledby='label-comment'>" : "");
							}
							echo "<td>", (support("move_col") ? "<input type='image' class='icon' name='add[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.8.1") . "' alt='+' title='" . lang(102) . "'> " . "<input type='image' class='icon' name='up[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=up.gif&version=4.8.1") . "' alt='↑' title='" . lang(103) . "'> " . "<input type='image' class='icon' name='down[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=down.gif&version=4.8.1") . "' alt='↓' title='" . lang(104) . "'> " : ""), ($Ce == "" || support("drop_col") ? "<input type='image' class='icon' name='drop_col[$t]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=cross.gif&version=4.8.1") . "' alt='x' title='" . lang(105) . "'>" : "");
						}
					}
					function
					process_fields(&$o)
					{
						$ke = 0;
						if ($_POST["up"]) {
							$wd = 0;
							foreach ($o
								as $z => $n) {
								if (key($_POST["up"]) == $z) {
									unset($o[$z]);
									array_splice($o, $wd, 0, array($n));
									break;
								}
								if (isset($n["field"])) $wd = $ke;
								$ke++;
							}
						} elseif ($_POST["down"]) {
							$Dc = false;
							foreach ($o
								as $z => $n) {
								if (isset($n["field"]) && $Dc) {
									unset($o[key($_POST["down"])]);
									array_splice($o, $ke, 0, array($Dc));
									break;
								}
								if (key($_POST["down"]) == $z) $Dc = $n;
								$ke++;
							}
						} elseif ($_POST["add"]) {
							$o = array_values($o);
							array_splice($o, key($_POST["add"]), 0, array(array()));
						} elseif (!$_POST["drop_col"]) return
							false;
						return
							true;
					}
					function
					normalize_enum($C)
					{
						return "'" . str_replace("'", "''", addcslashes(stripcslashes(str_replace($C[0][0] . $C[0][0], $C[0][0], substr($C[0], 1, -1))), '\\')) . "'";
					}
					function
					grant($Hc, $jf, $e, $qe)
					{
						if (!$jf) return
							true;
						if ($jf == array("ALL PRIVILEGES", "GRANT OPTION")) return ($Hc == "GRANT" ? queries("$Hc ALL PRIVILEGES$qe WITH GRANT OPTION") : queries("$Hc ALL PRIVILEGES$qe") && queries("$Hc GRANT OPTION$qe"));
						return
							queries("$Hc " . preg_replace('~(GRANT OPTION)\([^)]*\)~', '\1', implode("$e, ", $jf) . $e) . $qe);
					}
					function
					drop_create($Lb, $i, $Mb, $Dg, $Nb, $B, $Td, $Rd, $Sd, $ne, $ce)
					{
						if ($_POST["drop"]) query_redirect($Lb, $B, $Td);
						elseif ($ne == "") query_redirect($i, $B, $Sd);
						elseif ($ne != $ce) {
							$mb = queries($i);
							queries_redirect($B, $Rd, $mb && queries($Lb));
							if ($mb) queries($Mb);
						} else
							queries_redirect($B, $Rd, queries($Dg) && queries($Nb) && queries($Lb) && queries($i));
					}
					function
					create_trigger($qe, $L)
					{
						global $y;
						$Ig = " $L[Timing] $L[Event]" . (preg_match('~ OF~', $L["Event"]) ? " $L[Of]" : "");
						return "CREATE TRIGGER " . idf_escape($L["Trigger"]) . ($y == "mssql" ? $qe . $Ig : $Ig . $qe) . rtrim(" $L[Type]\n$L[Statement]", ";") . ";";
					}
					function
					create_routine($Ff, $L)
					{
						global $dd, $y;
						$P = array();
						$o = (array)$L["fields"];
						ksort($o);
						foreach ($o
							as $n) {
							if ($n["field"] != "") $P[] = (preg_match("~^($dd)\$~", $n["inout"]) ? "$n[inout] " : "") . idf_escape($n["field"]) . process_type($n, "CHARACTER SET");
						}
						$Ab = rtrim("\n$L[definition]", ";");
						return "CREATE $Ff " . idf_escape(trim($L["name"])) . " (" . implode(", ", $P) . ")" . (isset($_GET["function"]) ? " RETURNS" . process_type($L["returns"], "CHARACTER SET") : "") . ($L["language"] ? " LANGUAGE $L[language]" : "") . ($y == "pgsql" ? " AS " . q($Ab) : "$Ab;");
					}
					function
					remove_definer($I)
					{
						return
							preg_replace('~^([A-Z =]+) DEFINER=`' . preg_replace('~@(.*)~', '`@`(%|\1)', logged_user()) . '`~', '\1', $I);
					}
					function
					format_foreign_key($p)
					{
						global $re;
						$k = $p["db"];
						$ge = $p["ns"];
						return " FOREIGN KEY (" . implode(", ", array_map('idf_escape', $p["source"])) . ") REFERENCES " . ($k != "" && $k != $_GET["db"] ? idf_escape($k) . "." : "") . ($ge != "" && $ge != $_GET["ns"] ? idf_escape($ge) . "." : "") . table($p["table"]) . " (" . implode(", ", array_map('idf_escape', $p["target"])) . ")" . (preg_match("~^($re)\$~", $p["on_delete"]) ? " ON DELETE $p[on_delete]" : "") . (preg_match("~^($re)\$~", $p["on_update"]) ? " ON UPDATE $p[on_update]" : "");
					}
					function
					tar_file($vc, $Ng)
					{
						$K = pack("a100a8a8a8a12a12", $vc, 644, 0, 0, decoct($Ng->size), decoct(time()));
						$Pa = 8 * 32;
						for ($t = 0; $t < strlen($K); $t++) $Pa += ord($K[$t]);
						$K .= sprintf("%06o", $Pa) . "\0 ";
						echo $K, str_repeat("\0", 512 - strlen($K));
						$Ng->send();
						echo
						str_repeat("\0", 511 - ($Ng->size + 511) % 512);
					}
					function
					ini_bytes($cd)
					{
						$X = ini_get($cd);
						switch (strtolower(substr($X, -1))) {
							case 'g':
								$X *= 1024;
							case 'm':
								$X *= 1024;
							case 'k':
								$X *= 1024;
						}
						return $X;
					}
					function
					doc_link($Te, $Eg = "<sup>?</sup>")
					{
						global $y, $g;
						$Sf = $g->server_info;
						$uh = preg_replace('~^(\d\.?\d).*~s', '\1', $Sf);
						$lh = array('sql' => "https://dev.mysql.com/doc/refman/$uh/en/", 'sqlite' => "https://www.sqlite.org/", 'pgsql' => "https://www.postgresql.org/docs/$uh/", 'mssql' => "https://msdn.microsoft.com/library/", 'oracle' => "https://www.oracle.com/pls/topic/lookup?ctx=db" . preg_replace('~^.* (\d+)\.(\d+)\.\d+\.\d+\.\d+.*~s', '\1\2', $Sf) . "&id=",);
						if (preg_match('~MariaDB~', $Sf)) {
							$lh['sql'] = "https://mariadb.com/kb/en/library/";
							$Te['sql'] = (isset($Te['mariadb']) ? $Te['mariadb'] : str_replace(".html", "/", $Te['sql']));
						}
						return ($Te[$y] ? "<a href='" . h($lh[$y] . $Te[$y]) . "'" . target_blank() . ">$Eg</a>" : "");
					}
					function
					ob_gzencode($lg)
					{
						return
							gzencode($lg);
					}
					function
					db_size($k)
					{
						global $g;
						if (!$g->select_db($k)) return "?";
						$K = 0;
						foreach (table_status() as $R) $K += $R["Data_length"] + $R["Index_length"];
						return
							format_number($K);
					}
					function
					set_utf8mb4($i)
					{
						global $g;
						static $P = false;
						if (!$P && preg_match('~\butf8mb4~i', $i)) {
							$P = true;
							echo "SET NAMES " . charset($g) . ";\n\n";
						}
					}
					function
					connect_error()
					{
						global $c, $g, $T, $m, $Kb;
						if (DB != "") {
							header("HTTP/1.1 404 Not Found");
							page_header(lang(26) . ": " . h(DB), lang(106), true);
						} else {
							if ($_POST["db"] && !$m) queries_redirect(substr(ME, 0, -1), lang(107), drop_databases($_POST["db"]));
							page_header(lang(108), $m, false);
							echo "<p class='links'>\n";
							foreach (array('database' => lang(109), 'privileges' => lang(60), 'processlist' => lang(110), 'variables' => lang(111), 'status' => lang(112),) as $z => $X) {
								if (support($z)) echo "<a href='" . h(ME) . "$z='>$X</a>\n";
							}
							echo "<p>" . lang(113, $Kb[DRIVER], "<b>" . h($g->server_info) . "</b>", "<b>$g->extension</b>") . "\n", "<p>" . lang(114, "<b>" . h(logged_user()) . "</b>") . "\n";
							$j = $c->databases();
							if ($j) {
								$Lf = support("scheme");
								$Xa = collations();
								echo "<form action='' method='post'>\n", "<table cellspacing='0' class='checkable'>\n", script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"), "<thead><tr>" . (support("database") ? "<td>" : "") . "<th>" . lang(26) . " - <a href='" . h(ME) . "refresh=1'>" . lang(115) . "</a>" . "<td>" . lang(116) . "<td>" . lang(117) . "<td>" . lang(118) . " - <a href='" . h(ME) . "dbsize=1'>" . lang(119) . "</a>" . script("qsl('a').onclick = partial(ajaxSetHtml, '" . js_escape(ME) . "script=connect');", "") . "</thead>\n";
								$j = ($_GET["dbsize"] ? count_tables($j) : array_flip($j));
								foreach ($j
									as $k => $S) {
									$Ef = h(ME) . "db=" . urlencode($k);
									$u = h("Db-" . $k);
									echo "<tr" . odd() . ">" . (support("database") ? "<td>" . checkbox("db[]", $k, in_array($k, (array)$_POST["db"]), "", "", "", $u) : ""), "<th><a href='$Ef' id='$u'>" . h($k) . "</a>";
									$Wa = h(db_collation($k, $Xa));
									echo "<td>" . (support("database") ? "<a href='$Ef" . ($Lf ? "&amp;ns=" : "") . "&amp;database=' title='" . lang(56) . "'>$Wa</a>" : $Wa), "<td align='right'><a href='$Ef&amp;schema=' id='tables-" . h($k) . "' title='" . lang(59) . "'>" . ($_GET["dbsize"] ? $S : "?") . "</a>", "<td align='right' id='size-" . h($k) . "'>" . ($_GET["dbsize"] ? db_size($k) : "?"), "\n";
								}
								echo "</table>\n", (support("database") ? "<div class='footer'><div>\n" . "<fieldset><legend>" . lang(120) . " <span id='selected'></span></legend><div>\n" . "<input type='hidden' name='all' value=''>" . script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^db/)); };") . "<input type='submit' name='drop' value='" . lang(121) . "'>" . confirm() . "\n" . "</div></fieldset>\n" . "</div></div>\n" : ""), "<input type='hidden' name='token' value='$T'>\n", "</form>\n", script("tableCheck();");
							}
						}
						page_footer("db");
					}
					if (isset($_GET["status"])) $_GET["variables"] = $_GET["status"];
					if (isset($_GET["import"])) $_GET["sql"] = $_GET["import"];
					if (!(DB != "" ? $g->select_db(DB) : isset($_GET["sql"]) || isset($_GET["dump"]) || isset($_GET["database"]) || isset($_GET["processlist"]) || isset($_GET["privileges"]) || isset($_GET["user"]) || isset($_GET["variables"]) || $_GET["script"] == "connect" || $_GET["script"] == "kill")) {
						if (DB != "" || $_GET["refresh"]) {
							restart_session();
							set_session("dbs", null);
						}
						connect_error();
						exit;
					}
					$re = "RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";
					class
					TmpFile
					{
						var $handler;
						var $size;
						function
						__construct()
						{
							$this->handler = tmpfile();
						}
						function
						write($hb)
						{
							$this->size += strlen($hb);
							fwrite($this->handler, $hb);
						}
						function
						send()
						{
							fseek($this->handler, 0);
							fpassthru($this->handler);
							fclose($this->handler);
						}
					}
					$bc = "'(?:''|[^'\\\\]|\\\\.)*'";
					$dd = "IN|OUT|INOUT";
					if (isset($_GET["select"]) && ($_POST["edit"] || $_POST["clone"]) && !$_POST["save"]) $_GET["edit"] = $_GET["select"];
					if (isset($_GET["callf"])) $_GET["call"] = $_GET["callf"];
					if (isset($_GET["function"])) $_GET["procedure"] = $_GET["function"];
					if (isset($_GET["download"])) {
						$b = $_GET["download"];
						$o = fields($b);
						header("Content-Type: application/octet-stream");
						header("Content-Disposition: attachment; filename=" . friendly_url("$b-" . implode("_", $_GET["where"])) . "." . friendly_url($_GET["field"]));
						$N = array(idf_escape($_GET["field"]));
						$J = $l->select($b, $N, array(where($_GET, $o)), $N);
						$L = ($J ? $J->fetch_row() : array());
						echo $l->value($L[0], $o[$_GET["field"]]);
						exit;
					} elseif (isset($_GET["table"])) {
						$b = $_GET["table"];
						$o = fields($b);
						if (!$o) $m = error();
						$R = table_status1($b, true);
						$E = $c->tableName($R);
						page_header(($o && is_view($R) ? $R['Engine'] == 'materialized view' ? lang(122) : lang(123) : lang(124)) . ": " . ($E != "" ? $E : h($b)), $m);
						$c->selectLinks($R);
						$bb = $R["Comment"];
						if ($bb != "") echo "<p class='nowrap'>" . lang(39) . ": " . h($bb) . "\n";
						if ($o) $c->tableStructurePrint($o);
						if (!is_view($R)) {
							if (support("indexes")) {
								echo "<h3 id='indexes'>" . lang(125) . "</h3>\n";
								$x = indexes($b);
								if ($x) $c->tableIndexesPrint($x);
								echo '<p class="links"><a href="' . h(ME) . 'indexes=' . urlencode($b) . '">' . lang(126) . "</a>\n";
							}
							if (fk_support($R)) {
								echo "<h3 id='foreign-keys'>" . lang(94) . "</h3>\n";
								$Bc = foreign_keys($b);
								if ($Bc) {
									echo "<table cellspacing='0'>\n", "<thead><tr><th>" . lang(127) . "<td>" . lang(128) . "<td>" . lang(97) . "<td>" . lang(96) . "<td></thead>\n";
									foreach ($Bc
										as $E => $p) {
										echo "<tr title='" . h($E) . "'>", "<th><i>" . implode("</i>, <i>", array_map('h', $p["source"])) . "</i>", "<td><a href='" . h($p["db"] != "" ? preg_replace('~db=[^&]*~', "db=" . urlencode($p["db"]), ME) : ($p["ns"] != "" ? preg_replace('~ns=[^&]*~', "ns=" . urlencode($p["ns"]), ME) : ME)) . "table=" . urlencode($p["table"]) . "'>" . ($p["db"] != "" ? "<b>" . h($p["db"]) . "</b>." : "") . ($p["ns"] != "" ? "<b>" . h($p["ns"]) . "</b>." : "") . h($p["table"]) . "</a>", "(<i>" . implode("</i>, <i>", array_map('h', $p["target"])) . "</i>)", "<td>" . h($p["on_delete"]) . "\n", "<td>" . h($p["on_update"]) . "\n", '<td><a href="' . h(ME . 'foreign=' . urlencode($b) . '&name=' . urlencode($E)) . '">' . lang(129) . '</a>';
									}
									echo "</table>\n";
								}
								echo '<p class="links"><a href="' . h(ME) . 'foreign=' . urlencode($b) . '">' . lang(130) . "</a>\n";
							}
						}
						if (support(is_view($R) ? "view_trigger" : "trigger")) {
							echo "<h3 id='triggers'>" . lang(131) . "</h3>\n";
							$Xg = triggers($b);
							if ($Xg) {
								echo "<table cellspacing='0'>\n";
								foreach ($Xg
									as $z => $X) echo "<tr valign='top'><td>" . h($X[0]) . "<td>" . h($X[1]) . "<th>" . h($z) . "<td><a href='" . h(ME . 'trigger=' . urlencode($b) . '&name=' . urlencode($z)) . "'>" . lang(129) . "</a>\n";
								echo "</table>\n";
							}
							echo '<p class="links"><a href="' . h(ME) . 'trigger=' . urlencode($b) . '">' . lang(132) . "</a>\n";
						}
					} elseif (isset($_GET["schema"])) {
						page_header(lang(59), "", array(), h(DB . ($_GET["ns"] ? ".$_GET[ns]" : "")));
						$vg = array();
						$wg = array();
						$da = ($_GET["schema"] ? $_GET["schema"] : $_COOKIE["adminer_schema-" . str_replace(".", "_", DB)]);
						preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~', $da, $Jd, PREG_SET_ORDER);
						foreach ($Jd
							as $t => $C) {
							$vg[$C[1]] = array($C[2], $C[3]);
							$wg[] = "\n\t'" . js_escape($C[1]) . "': [ $C[2], $C[3] ]";
						}
						$Pg = 0;
						$Ca = -1;
						$Kf = array();
						$wf = array();
						$_d = array();
						foreach (table_status('', true) as $Q => $R) {
							if (is_view($R)) continue;
							$Ze = 0;
							$Kf[$Q]["fields"] = array();
							foreach (fields($Q) as $E => $n) {
								$Ze += 1.25;
								$n["pos"] = $Ze;
								$Kf[$Q]["fields"][$E] = $n;
							}
							$Kf[$Q]["pos"] = ($vg[$Q] ? $vg[$Q] : array($Pg, 0));
							foreach ($c->foreignKeys($Q) as $X) {
								if (!$X["db"]) {
									$yd = $Ca;
									if ($vg[$Q][1] || $vg[$X["table"]][1]) $yd = min(floatval($vg[$Q][1]), floatval($vg[$X["table"]][1])) - 1;
									else $Ca -= .1;
									while ($_d[(string)$yd]) $yd -= .0001;
									$Kf[$Q]["references"][$X["table"]][(string)$yd] = array($X["source"], $X["target"]);
									$wf[$X["table"]][$Q][(string)$yd] = $X["target"];
									$_d[(string)$yd] = true;
								}
							}
							$Pg = max($Pg, $Kf[$Q]["pos"][0] + 2.5 + $Ze);
						}
						echo '<div id="schema" style="height: ', $Pg, 'em;">
<script', nonce(), '>
qs(\'#schema\').onselectstart = function () { return false; };
var tablePos = {', implode(",", $wg) . "\n", '};
var em = qs(\'#schema\').offsetHeight / ', $Pg, ';
document.onmousemove = schemaMousemove;
document.onmouseup = partialArg(schemaMouseup, \'', js_escape(DB), '\');
</script>
';
						foreach ($Kf
							as $E => $Q) {
							echo "<div class='table' style='top: " . $Q["pos"][0] . "em; left: " . $Q["pos"][1] . "em;'>", '<a href="' . h(ME) . 'table=' . urlencode($E) . '"><b>' . h($E) . "</b></a>", script("qsl('div').onmousedown = schemaMousedown;");
							foreach ($Q["fields"] as $n) {
								$X = '<span' . type_class($n["type"]) . ' title="' . h($n["full_type"] . ($n["null"] ? " NULL" : '')) . '">' . h($n["field"]) . '</span>';
								echo "<br>" . ($n["primary"] ? "<i>$X</i>" : $X);
							}
							foreach ((array)$Q["references"] as $Bg => $xf) {
								foreach ($xf
									as $yd => $tf) {
									$zd = $yd - $vg[$E][1];
									$t = 0;
									foreach ($tf[0] as $bg) echo "\n<div class='references' title='" . h($Bg) . "' id='refs$yd-" . ($t++) . "' style='left: $zd" . "em; top: " . $Q["fields"][$bg]["pos"] . "em; padding-top: .5em;'><div style='border-top: 1px solid Gray; width: " . (-$zd) . "em;'></div></div>";
								}
							}
							foreach ((array)$wf[$E] as $Bg => $xf) {
								foreach ($xf
									as $yd => $e) {
									$zd = $yd - $vg[$E][1];
									$t = 0;
									foreach ($e
										as $Ag) echo "\n<div class='references' title='" . h($Bg) . "' id='refd$yd-" . ($t++) . "' style='left: $zd" . "em; top: " . $Q["fields"][$Ag]["pos"] . "em; height: 1.25em; background: url(" . h(preg_replace("~\\?.*~", "", ME) . "?file=arrow.gif) no-repeat right center;&version=4.8.1") . "'><div style='height: .5em; border-bottom: 1px solid Gray; width: " . (-$zd) . "em;'></div></div>";
								}
							}
							echo "\n</div>\n";
						}
						foreach ($Kf
							as $E => $Q) {
							foreach ((array)$Q["references"] as $Bg => $xf) {
								foreach ($xf
									as $yd => $tf) {
									$Vd = $Pg;
									$Nd = -10;
									foreach ($tf[0] as $z => $bg) {
										$af = $Q["pos"][0] + $Q["fields"][$bg]["pos"];
										$bf = $Kf[$Bg]["pos"][0] + $Kf[$Bg]["fields"][$tf[1][$z]]["pos"];
										$Vd = min($Vd, $af, $bf);
										$Nd = max($Nd, $af, $bf);
									}
									echo "<div class='references' id='refl$yd' style='left: $yd" . "em; top: $Vd" . "em; padding: .5em 0;'><div style='border-right: 1px solid Gray; margin-top: 1px; height: " . ($Nd - $Vd) . "em;'></div></div>\n";
								}
							}
						}
						echo '</div>
<p class="links"><a href="', h(ME . "schema=" . urlencode($da)), '" id="schema-link">', lang(133), '</a>
';
					} elseif (isset($_GET["dump"])) {
						$b = $_GET["dump"];
						if ($_POST && !$m) {
							$kb = "";
							foreach (array("output", "format", "db_style", "routines", "events", "table_style", "auto_increment", "triggers", "data_style") as $z) $kb .= "&$z=" . urlencode($_POST[$z]);
							cookie("adminer_export", substr($kb, 1));
							$S = array_flip((array)$_POST["tables"]) + array_flip((array)$_POST["data"]);
							$nc = dump_headers((count($S) == 1 ? key($S) : DB), (DB == "" || count($S) > 1));
							$ld = preg_match('~sql~', $_POST["format"]);
							if ($ld) {
								echo "-- Adminer $fa " . $Kb[DRIVER] . " " . str_replace("\n", " ", $g->server_info) . " dump\n\n";
								if ($y == "sql") {
									echo "SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
" . ($_POST["data_style"] ? "SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
" : "") . "
";
									$g->query("SET time_zone = '+00:00'");
									$g->query("SET sql_mode = ''");
								}
							}
							$ng = $_POST["db_style"];
							$j = array(DB);
							if (DB == "") {
								$j = $_POST["databases"];
								if (is_string($j)) $j = explode("\n", rtrim(str_replace("\r", "", $j), "\n"));
							}
							foreach ((array)$j
								as $k) {
								$c->dumpDatabase($k);
								if ($g->select_db($k)) {
									if ($ld && preg_match('~CREATE~', $ng) && ($i = $g->result("SHOW CREATE DATABASE " . idf_escape($k), 1))) {
										set_utf8mb4($i);
										if ($ng == "DROP+CREATE") echo "DROP DATABASE IF EXISTS " . idf_escape($k) . ";\n";
										echo "$i;\n";
									}
									if ($ld) {
										if ($ng) echo
										use_sql($k) . ";\n\n";
										$He = "";
										if ($_POST["routines"]) {
											foreach (array("FUNCTION", "PROCEDURE") as $Ff) {
												foreach (get_rows("SHOW $Ff STATUS WHERE Db = " . q($k), null, "-- ") as $L) {
													$i = remove_definer($g->result("SHOW CREATE $Ff " . idf_escape($L["Name"]), 2));
													set_utf8mb4($i);
													$He .= ($ng != 'DROP+CREATE' ? "DROP $Ff IF EXISTS " . idf_escape($L["Name"]) . ";;\n" : "") . "$i;;\n\n";
												}
											}
										}
										if ($_POST["events"]) {
											foreach (get_rows("SHOW EVENTS", null, "-- ") as $L) {
												$i = remove_definer($g->result("SHOW CREATE EVENT " . idf_escape($L["Name"]), 3));
												set_utf8mb4($i);
												$He .= ($ng != 'DROP+CREATE' ? "DROP EVENT IF EXISTS " . idf_escape($L["Name"]) . ";;\n" : "") . "$i;;\n\n";
											}
										}
										if ($He) echo "DELIMITER ;;\n\n$He" . "DELIMITER ;\n\n";
									}
									if ($_POST["table_style"] || $_POST["data_style"]) {
										$wh = array();
										foreach (table_status('', true) as $E => $R) {
											$Q = (DB == "" || in_array($E, (array)$_POST["tables"]));
											$sb = (DB == "" || in_array($E, (array)$_POST["data"]));
											if ($Q || $sb) {
												if ($nc == "tar") {
													$Ng = new
														TmpFile;
													ob_start(array($Ng, 'write'), 1e5);
												}
												$c->dumpTable($E, ($Q ? $_POST["table_style"] : ""), (is_view($R) ? 2 : 0));
												if (is_view($R)) $wh[] = $E;
												elseif ($sb) {
													$o = fields($E);
													$c->dumpData($E, $_POST["data_style"], "SELECT *" . convert_fields($o, $o) . " FROM " . table($E));
												}
												if ($ld && $_POST["triggers"] && $Q && ($Xg = trigger_sql($E))) echo "\nDELIMITER ;;\n$Xg\nDELIMITER ;\n";
												if ($nc == "tar") {
													ob_end_flush();
													tar_file((DB != "" ? "" : "$k/") . "$E.csv", $Ng);
												} elseif ($ld) echo "\n";
											}
										}
										if (function_exists('foreign_keys_sql')) {
											foreach (table_status('', true) as $E => $R) {
												$Q = (DB == "" || in_array($E, (array)$_POST["tables"]));
												if ($Q && !is_view($R)) echo
												foreign_keys_sql($E);
											}
										}
										foreach ($wh
											as $vh) $c->dumpTable($vh, $_POST["table_style"], 1);
										if ($nc == "tar") echo
										pack("x512");
									}
								}
							}
							if ($ld) echo "-- " . $g->result("SELECT NOW()") . "\n";
							exit;
						}
						page_header(lang(62), $m, ($_GET["export"] != "" ? array("table" => $_GET["export"]) : array()), h(DB));
						echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
';
						$wb = array('', 'USE', 'DROP+CREATE', 'CREATE');
						$xg = array('', 'DROP+CREATE', 'CREATE');
						$tb = array('', 'TRUNCATE+INSERT', 'INSERT');
						if ($y == "sql") $tb[] = 'INSERT+UPDATE';
						parse_str($_COOKIE["adminer_export"], $L);
						if (!$L) $L = array("output" => "text", "format" => "sql", "db_style" => (DB != "" ? "" : "CREATE"), "table_style" => "DROP+CREATE", "data_style" => "INSERT");
						if (!isset($L["events"])) {
							$L["routines"] = $L["events"] = ($_GET["dump"] == "");
							$L["triggers"] = $L["table_style"];
						}
						echo "<tr><th>" . lang(134) . "<td>" . html_select("output", $c->dumpOutput(), $L["output"], 0) . "\n";
						echo "<tr><th>" . lang(135) . "<td>" . html_select("format", $c->dumpFormat(), $L["format"], 0) . "\n";
						echo ($y == "sqlite" ? "" : "<tr><th>" . lang(26) . "<td>" . html_select('db_style', $wb, $L["db_style"]) . (support("routine") ? checkbox("routines", 1, $L["routines"], lang(136)) : "") . (support("event") ? checkbox("events", 1, $L["events"], lang(137)) : "")), "<tr><th>" . lang(117) . "<td>" . html_select('table_style', $xg, $L["table_style"]) . checkbox("auto_increment", 1, $L["auto_increment"], lang(40)) . (support("trigger") ? checkbox("triggers", 1, $L["triggers"], lang(131)) : ""), "<tr><th>" . lang(138) . "<td>" . html_select('data_style', $tb, $L["data_style"]), '</table>
<p><input type="submit" value="', lang(62), '">
<input type="hidden" name="token" value="', $T, '">

<table cellspacing="0">
', script("qsl('table').onclick = dumpClick;");
						$ef = array();
						if (DB != "") {
							$Na = ($b != "" ? "" : " checked");
							echo "<thead><tr>", "<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$Na>" . lang(117) . "</label>" . script("qs('#check-tables').onclick = partial(formCheck, /^tables\\[/);", ""), "<th style='text-align: right;'><label class='block'>" . lang(138) . "<input type='checkbox' id='check-data'$Na></label>" . script("qs('#check-data').onclick = partial(formCheck, /^data\\[/);", ""), "</thead>\n";
							$wh = "";
							$yg = tables_list();
							foreach ($yg
								as $E => $U) {
								$df = preg_replace('~_.*~', '', $E);
								$Na = ($b == "" || $b == (substr($b, -1) == "%" ? "$df%" : $E));
								$gf = "<tr><td>" . checkbox("tables[]", $E, $Na, $E, "", "block");
								if ($U !== null && !preg_match('~table~i', $U)) $wh .= "$gf\n";
								else
									echo "$gf<td align='right'><label class='block'><span id='Rows-" . h($E) . "'></span>" . checkbox("data[]", $E, $Na) . "</label>\n";
								$ef[$df]++;
							}
							echo $wh;
							if ($yg) echo
							script("ajaxSetHtml('" . js_escape(ME) . "script=db');");
						} else {
							echo "<thead><tr><th style='text-align: left;'>", "<label class='block'><input type='checkbox' id='check-databases'" . ($b == "" ? " checked" : "") . ">" . lang(26) . "</label>", script("qs('#check-databases').onclick = partial(formCheck, /^databases\\[/);", ""), "</thead>\n";
							$j = $c->databases();
							if ($j) {
								foreach ($j
									as $k) {
									if (!information_schema($k)) {
										$df = preg_replace('~_.*~', '', $k);
										echo "<tr><td>" . checkbox("databases[]", $k, $b == "" || $b == "$df%", $k, "", "block") . "\n";
										$ef[$df]++;
									}
								}
							} else
								echo "<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";
						}
						echo '</table>
</form>
';
						$xc = true;
						foreach ($ef
							as $z => $X) {
							if ($z != "" && $X > 1) {
								echo ($xc ? "<p>" : " ") . "<a href='" . h(ME) . "dump=" . urlencode("$z%") . "'>" . h($z) . "</a>";
								$xc = false;
							}
						}
					} elseif (isset($_GET["privileges"])) {
						page_header(lang(60));
						echo '<p class="links"><a href="' . h(ME) . 'user=">' . lang(139) . "</a>";
						$J = $g->query("SELECT User, Host FROM mysql." . (DB == "" ? "user" : "db WHERE " . q(DB) . " LIKE Db") . " ORDER BY Host, User");
						$Hc = $J;
						if (!$J) $J = $g->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");
						echo "<form action=''><p>\n";
						hidden_fields_get();
						echo "<input type='hidden' name='db' value='" . h(DB) . "'>\n", ($Hc ? "" : "<input type='hidden' name='grant' value=''>\n"), "<table cellspacing='0'>\n", "<thead><tr><th>" . lang(24) . "<th>" . lang(23) . "<th></thead>\n";
						while ($L = $J->fetch_assoc()) echo '<tr' . odd() . '><td>' . h($L["User"]) . "<td>" . h($L["Host"]) . '<td><a href="' . h(ME . 'user=' . urlencode($L["User"]) . '&host=' . urlencode($L["Host"])) . '">' . lang(10) . "</a>\n";
						if (!$Hc || DB != "") echo "<tr" . odd() . "><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='" . lang(10) . "'>\n";
						echo "</table>\n", "</form>\n";
					} elseif (isset($_GET["sql"])) {
						if (!$m && $_POST["export"]) {
							dump_headers("sql");
							$c->dumpTable("", "");
							$c->dumpData("", "table", $_POST["query"]);
							exit;
						}
						restart_session();
						$Uc = &get_session("queries");
						$Tc = &$Uc[DB];
						if (!$m && $_POST["clear"]) {
							$Tc = array();
							redirect(remove_from_uri("history"));
						}
						page_header((isset($_GET["import"]) ? lang(61) : lang(53)), $m);
						if (!$m && $_POST) {
							$q = false;
							if (!isset($_GET["import"])) $I = $_POST["query"];
							elseif ($_POST["webfile"]) {
								$eg = $c->importServerPath();
								$q = @fopen((file_exists($eg) ? $eg : "compress.zlib://$eg.gz"), "rb");
								$I = ($q ? fread($q, 1e6) : false);
							} else $I = get_file("sql_file", true);
							if (is_string($I)) {
								if (function_exists('memory_get_usage')) @ini_set("memory_limit", max(ini_bytes("memory_limit"), 2 * strlen($I) + memory_get_usage() + 8e6));
								if ($I != "" && strlen($I) < 1e6) {
									$H = $I . (preg_match("~;[ \t\r\n]*\$~", $I) ? "" : ";");
									if (!$Tc || reset(end($Tc)) != $H) {
										restart_session();
										$Tc[] = array($H, time());
										set_session("queries", $Uc);
										stop_session();
									}
								}
								$cg = "(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";
								$Cb = ";";
								$ke = 0;
								$Yb = true;
								$h = connect();
								if (is_object($h) && DB != "") {
									$h->select_db(DB);
									if ($_GET["ns"] != "") set_schema($_GET["ns"], $h);
								}
								$ab = 0;
								$dc = array();
								$Ne = '[\'"' . ($y == "sql" ? '`#' : ($y == "sqlite" ? '`[' : ($y == "mssql" ? '[' : ''))) . ']|/\*|-- |$' . ($y == "pgsql" ? '|\$[^$]*\$' : '');
								$Qg = microtime(true);
								parse_str($_COOKIE["adminer_export"], $la);
								$Pb = $c->dumpFormat();
								unset($Pb["sql"]);
								while ($I != "") {
									if (!$ke && preg_match("~^$cg*+DELIMITER\\s+(\\S+)~i", $I, $C)) {
										$Cb = $C[1];
										$I = substr($I, strlen($C[0]));
									} else {
										preg_match('(' . preg_quote($Cb) . "\\s*|$Ne)", $I, $C, PREG_OFFSET_CAPTURE, $ke);
										list($Dc, $Ze) = $C[0];
										if (!$Dc && $q && !feof($q)) $I .= fread($q, 1e5);
										else {
											if (!$Dc && rtrim($I) == "") break;
											$ke = $Ze + strlen($Dc);
											if ($Dc && rtrim($Dc) != $Cb) {
												while (preg_match('(' . ($Dc == '/*' ? '\*/' : ($Dc == '[' ? ']' : (preg_match('~^-- |^#~', $Dc) ? "\n" : preg_quote($Dc) . "|\\\\."))) . '|$)s', $I, $C, PREG_OFFSET_CAPTURE, $ke)) {
													$If = $C[0][0];
													if (!$If && $q && !feof($q)) $I .= fread($q, 1e5);
													else {
														$ke = $C[0][1] + strlen($If);
														if ($If[0] != "\\") break;
													}
												}
											} else {
												$Yb = false;
												$H = substr($I, 0, $Ze);
												$ab++;
												$gf = "<pre id='sql-$ab'><code class='jush-$y'>" . $c->sqlCommandQuery($H) . "</code></pre>\n";
												if ($y == "sqlite" && preg_match("~^$cg*+ATTACH\\b~i", $H, $C)) {
													echo $gf, "<p class='error'>" . lang(140) . "\n";
													$dc[] = " <a href='#sql-$ab'>$ab</a>";
													if ($_POST["error_stops"]) break;
												} else {
													if (!$_POST["only_errors"]) {
														echo $gf;
														ob_flush();
														flush();
													}
													$hg = microtime(true);
													if ($g->multi_query($H) && is_object($h) && preg_match("~^$cg*+USE\\b~i", $H)) $h->query($H);
													do {
														$J = $g->store_result();
														if ($g->error) {
															echo ($_POST["only_errors"] ? $gf : ""), "<p class='error'>" . lang(141) . ($g->errno ? " ($g->errno)" : "") . ": " . error() . "\n";
															$dc[] = " <a href='#sql-$ab'>$ab</a>";
															if ($_POST["error_stops"]) break
																2;
														} else {
															$Gg = " <span class='time'>(" . format_time($hg) . ")</span>" . (strlen($H) < 1000 ? " <a href='" . h(ME) . "sql=" . urlencode(trim($H)) . "'>" . lang(10) . "</a>" : "");
															$na = $g->affected_rows;
															$zh = ($_POST["only_errors"] ? "" : $l->warnings());
															$_h = "warnings-$ab";
															if ($zh) $Gg .= ", <a href='#$_h'>" . lang(35) . "</a>" . script("qsl('a').onclick = partial(toggle, '$_h');", "");
															$lc = null;
															$mc = "explain-$ab";
															if (is_object($J)) {
																$_ = $_POST["limit"];
																$Be = select($J, $h, array(), $_);
																if (!$_POST["only_errors"]) {
																	echo "<form action='' method='post'>\n";
																	$he = $J->num_rows;
																	echo "<p>" . ($he ? ($_ && $he > $_ ? lang(142, $_) : "") . lang(143, $he) : ""), $Gg;
																	if ($h && preg_match("~^($cg|\\()*+SELECT\\b~i", $H) && ($lc = explain($h, $H))) echo ", <a href='#$mc'>Explain</a>" . script("qsl('a').onclick = partial(toggle, '$mc');", "");
																	$u = "export-$ab";
																	echo ", <a href='#$u'>" . lang(62) . "</a>" . script("qsl('a').onclick = partial(toggle, '$u');", "") . "<span id='$u' class='hidden'>: " . html_select("output", $c->dumpOutput(), $la["output"]) . " " . html_select("format", $Pb, $la["format"]) . "<input type='hidden' name='query' value='" . h($H) . "'>" . " <input type='submit' name='export' value='" . lang(62) . "'><input type='hidden' name='token' value='$T'></span>\n" . "</form>\n";
																}
															} else {
																if (preg_match("~^$cg*+(CREATE|DROP|ALTER)$cg++(DATABASE|SCHEMA)\\b~i", $H)) {
																	restart_session();
																	set_session("dbs", null);
																	stop_session();
																}
																if (!$_POST["only_errors"]) echo "<p class='message' title='" . h($g->info) . "'>" . lang(144, $na) . "$Gg\n";
															}
															echo ($zh ? "<div id='$_h' class='hidden'>\n$zh</div>\n" : "");
															if ($lc) {
																echo "<div id='$mc' class='hidden'>\n";
																select($lc, $h, $Be);
																echo "</div>\n";
															}
														}
														$hg = microtime(true);
													} while ($g->next_result());
												}
												$I = substr($I, $ke);
												$ke = 0;
											}
										}
									}
								}
								if ($Yb) echo "<p class='message'>" . lang(145) . "\n";
								elseif ($_POST["only_errors"]) {
									echo "<p class='message'>" . lang(146, $ab - count($dc)), " <span class='time'>(" . format_time($Qg) . ")</span>\n";
								} elseif ($dc && $ab > 1) echo "<p class='error'>" . lang(141) . ": " . implode("", $dc) . "\n";
							} else
								echo "<p class='error'>" . upload_error($I) . "\n";
						}
						echo '
<form action="" method="post" enctype="multipart/form-data" id="form">
';
						$jc = "<input type='submit' value='" . lang(147) . "' title='Ctrl+Enter'>";
						if (!isset($_GET["import"])) {
							$H = $_GET["sql"];
							if ($_POST) $H = $_POST["query"];
							elseif ($_GET["history"] == "all") $H = $Tc;
							elseif ($_GET["history"] != "") $H = $Tc[$_GET["history"]][0];
							echo "<p>";
							textarea("query", $H, 20);
							echo
							script(($_POST ? "" : "qs('textarea').focus();\n") . "qs('#form').onsubmit = partial(sqlSubmit, qs('#form'), '" . js_escape(remove_from_uri("sql|limit|error_stops|only_errors|history")) . "');"), "<p>$jc\n", lang(148) . ": <input type='number' name='limit' class='size' value='" . h($_POST ? $_POST["limit"] : $_GET["limit"]) . "'>\n";
						} else {
							echo "<fieldset><legend>" . lang(149) . "</legend><div>";
							$Mc = (extension_loaded("zlib") ? "[.gz]" : "");
							echo (ini_bool("file_uploads") ? "SQL$Mc (&lt; " . ini_get("upload_max_filesize") . "B): <input type='file' name='sql_file[]' multiple>\n$jc" : lang(150)), "</div></fieldset>\n";
							$Zc = $c->importServerPath();
							if ($Zc) {
								echo "<fieldset><legend>" . lang(151) . "</legend><div>", lang(152, "<code>" . h($Zc) . "$Mc</code>"), ' <input type="submit" name="webfile" value="' . lang(153) . '">', "</div></fieldset>\n";
							}
							echo "<p>";
						}
						echo
						checkbox("error_stops", 1, ($_POST ? $_POST["error_stops"] : isset($_GET["import"]) || $_GET["error_stops"]), lang(154)) . "\n", checkbox("only_errors", 1, ($_POST ? $_POST["only_errors"] : isset($_GET["import"]) || $_GET["only_errors"]), lang(155)) . "\n", "<input type='hidden' name='token' value='$T'>\n";
						if (!isset($_GET["import"]) && $Tc) {
							print_fieldset("history", lang(156), $_GET["history"] != "");
							for ($X = end($Tc); $X; $X = prev($Tc)) {
								$z = key($Tc);
								list($H, $Gg, $Tb) = $X;
								echo '<a href="' . h(ME . "sql=&history=$z") . '">' . lang(10) . "</a>" . " <span class='time' title='" . @date('Y-m-d', $Gg) . "'>" . @date("H:i:s", $Gg) . "</span>" . " <code class='jush-$y'>" . shorten_utf8(ltrim(str_replace("\n", " ", str_replace("\r", "", preg_replace('~^(#|-- ).*~m', '', $H)))), 80, "</code>") . ($Tb ? " <span class='time'>($Tb)</span>" : "") . "<br>\n";
							}
							echo "<input type='submit' name='clear' value='" . lang(157) . "'>\n", "<a href='" . h(ME . "sql=&history=all") . "'>" . lang(158) . "</a>\n", "</div></fieldset>\n";
						}
						echo '</form>
';
					} elseif (isset($_GET["edit"])) {
						$b = $_GET["edit"];
						$o = fields($b);
						$Z = (isset($_GET["select"]) ? ($_POST["check"] && count($_POST["check"]) == 1 ? where_check($_POST["check"][0], $o) : "") : where($_GET, $o));
						$ih = (isset($_GET["select"]) ? $_POST["edit"] : $Z);
						foreach ($o
							as $E => $n) {
							if (!isset($n["privileges"][$ih ? "update" : "insert"]) || $c->fieldName($n) == "" || $n["generated"]) unset($o[$E]);
						}
						if ($_POST && !$m && !isset($_GET["select"])) {
							$B = $_POST["referer"];
							if ($_POST["insert"]) $B = ($ih ? null : $_SERVER["REQUEST_URI"]);
							elseif (!preg_match('~^.+&select=.+$~', $B)) $B = ME . "select=" . urlencode($b);
							$x = indexes($b);
							$dh = unique_array($_GET["where"], $x);
							$pf = "\nWHERE $Z";
							if (isset($_POST["delete"])) queries_redirect($B, lang(159), $l->delete($b, $pf, !$dh));
							else {
								$P = array();
								foreach ($o
									as $E => $n) {
									$X = process_input($n);
									if ($X !== false && $X !== null) $P[idf_escape($E)] = $X;
								}
								if ($ih) {
									if (!$P) redirect($B);
									queries_redirect($B, lang(160), $l->update($b, $P, $pf, !$dh));
									if (is_ajax()) {
										page_headers();
										page_messages($m);
										exit;
									}
								} else {
									$J = $l->insert($b, $P);
									$xd = ($J ? last_id() : 0);
									queries_redirect($B, lang(161, ($xd ? " $xd" : "")), $J);
								}
							}
						}
						$L = null;
						if ($_POST["save"]) $L = (array)$_POST["fields"];
						elseif ($Z) {
							$N = array();
							foreach ($o
								as $E => $n) {
								if (isset($n["privileges"]["select"])) {
									$ua = convert_field($n);
									if ($_POST["clone"] && $n["auto_increment"]) $ua = "''";
									if ($y == "sql" && preg_match("~enum|set~", $n["type"])) $ua = "1*" . idf_escape($E);
									$N[] = ($ua ? "$ua AS " : "") . idf_escape($E);
								}
							}
							$L = array();
							if (!support("table")) $N = array("*");
							if ($N) {
								$J = $l->select($b, $N, array($Z), $N, array(), (isset($_GET["select"]) ? 2 : 1));
								if (!$J) $m = error();
								else {
									$L = $J->fetch_assoc();
									if (!$L) $L = false;
								}
								if (isset($_GET["select"]) && (!$L || $J->fetch_assoc())) $L = null;
							}
						}
						if (!support("table") && !$o) {
							if (!$Z) {
								$J = $l->select($b, array("*"), $Z, array("*"));
								$L = ($J ? $J->fetch_assoc() : false);
								if (!$L) $L = array($l->primary => "");
							}
							if ($L) {
								foreach ($L
									as $z => $X) {
									if (!$Z) $L[$z] = null;
									$o[$z] = array("field" => $z, "null" => ($z != $l->primary), "auto_increment" => ($z == $l->primary));
								}
							}
						}
						edit_form($b, $o, $L, $ih);
					} elseif (isset($_GET["create"])) {
						$b = $_GET["create"];
						$Oe = array();
						foreach (array('HASH', 'LINEAR HASH', 'KEY', 'LINEAR KEY', 'RANGE', 'LIST') as $z) $Oe[$z] = $z;
						$vf = referencable_primary($b);
						$Bc = array();
						foreach ($vf
							as $ug => $n) $Bc[str_replace("`", "``", $ug) . "`" . str_replace("`", "``", $n["field"])] = $ug;
						$Ee = array();
						$R = array();
						if ($b != "") {
							$Ee = fields($b);
							$R = table_status($b);
							if (!$R) $m = lang(9);
						}
						$L = $_POST;
						$L["fields"] = (array)$L["fields"];
						if ($L["auto_increment_col"]) $L["fields"][$L["auto_increment_col"]]["auto_increment"] = true;
						if ($_POST) set_adminer_settings(array("comments" => $_POST["comments"], "defaults" => $_POST["defaults"]));
						if ($_POST && !process_fields($L["fields"]) && !$m) {
							if ($_POST["drop"]) queries_redirect(substr(ME, 0, -1), lang(162), drop_tables(array($b)));
							else {
								$o = array();
								$ra = array();
								$mh = false;
								$_c = array();
								$De = reset($Ee);
								$pa = " FIRST";
								foreach ($L["fields"] as $z => $n) {
									$p = $Bc[$n["type"]];
									$Yg = ($p !== null ? $vf[$p] : $n);
									if ($n["field"] != "") {
										if (!$n["has_default"]) $n["default"] = null;
										if ($z == $L["auto_increment_col"]) $n["auto_increment"] = true;
										$lf = process_field($n, $Yg);
										$ra[] = array($n["orig"], $lf, $pa);
										if (!$De || $lf != process_field($De, $De)) {
											$o[] = array($n["orig"], $lf, $pa);
											if ($n["orig"] != "" || $pa) $mh = true;
										}
										if ($p !== null) $_c[idf_escape($n["field"])] = ($b != "" && $y != "sqlite" ? "ADD" : " ") . format_foreign_key(array('table' => $Bc[$n["type"]], 'source' => array($n["field"]), 'target' => array($Yg["field"]), 'on_delete' => $n["on_delete"],));
										$pa = " AFTER " . idf_escape($n["field"]);
									} elseif ($n["orig"] != "") {
										$mh = true;
										$o[] = array($n["orig"]);
									}
									if ($n["orig"] != "") {
										$De = next($Ee);
										if (!$De) $pa = "";
									}
								}
								$Qe = "";
								if ($Oe[$L["partition_by"]]) {
									$Re = array();
									if ($L["partition_by"] == 'RANGE' || $L["partition_by"] == 'LIST') {
										foreach (array_filter($L["partition_names"]) as $z => $X) {
											$Y = $L["partition_values"][$z];
											$Re[] = "\n  PARTITION " . idf_escape($X) . " VALUES " . ($L["partition_by"] == 'RANGE' ? "LESS THAN" : "IN") . ($Y != "" ? " ($Y)" : " MAXVALUE");
										}
									}
									$Qe .= "\nPARTITION BY $L[partition_by]($L[partition])" . ($Re ? " (" . implode(",", $Re) . "\n)" : ($L["partitions"] ? " PARTITIONS " . (+$L["partitions"]) : ""));
								} elseif (support("partitioning") && preg_match("~partitioned~", $R["Create_options"])) $Qe .= "\nREMOVE PARTITIONING";
								$D = lang(163);
								if ($b == "") {
									cookie("adminer_engine", $L["Engine"]);
									$D = lang(164);
								}
								$E = trim($L["name"]);
								queries_redirect(ME . (support("table") ? "table=" : "select=") . urlencode($E), $D, alter_table($b, $E, ($y == "sqlite" && ($mh || $_c) ? $ra : $o), $_c, ($L["Comment"] != $R["Comment"] ? $L["Comment"] : null), ($L["Engine"] && $L["Engine"] != $R["Engine"] ? $L["Engine"] : ""), ($L["Collation"] && $L["Collation"] != $R["Collation"] ? $L["Collation"] : ""), ($L["Auto_increment"] != "" ? number($L["Auto_increment"]) : ""), $Qe));
							}
						}
						page_header(($b != "" ? lang(33) : lang(63)), $m, array("table" => $b), h($b));
						if (!$_POST) {
							$L = array("Engine" => $_COOKIE["adminer_engine"], "fields" => array(array("field" => "", "type" => (isset($ah["int"]) ? "int" : (isset($ah["integer"]) ? "integer" : "")), "on_update" => "")), "partition_names" => array(""),);
							if ($b != "") {
								$L = $R;
								$L["name"] = $b;
								$L["fields"] = array();
								if (!$_GET["auto_increment"]) $L["Auto_increment"] = "";
								foreach ($Ee
									as $n) {
									$n["has_default"] = isset($n["default"]);
									$L["fields"][] = $n;
								}
								if (support("partitioning")) {
									$Fc = "FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = " . q(DB) . " AND TABLE_NAME = " . q($b);
									$J = $g->query("SELECT PARTITION_METHOD, PARTITION_ORDINAL_POSITION, PARTITION_EXPRESSION $Fc ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");
									list($L["partition_by"], $L["partitions"], $L["partition"]) = $J->fetch_row();
									$Re = get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $Fc AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");
									$Re[""] = "";
									$L["partition_names"] = array_keys($Re);
									$L["partition_values"] = array_values($Re);
								}
							}
						}
						$Xa = collations();
						$ac = engines();
						foreach ($ac
							as $Zb) {
							if (!strcasecmp($Zb, $L["Engine"])) {
								$L["Engine"] = $Zb;
								break;
							}
						}
						echo '
<form action="" method="post" id="form">
<p>
';
						if (support("columns") || $b == "") {
							echo
							lang(165), ': <input name="name" data-maxlength="64" value="', h($L["name"]), '" autocapitalize="off">
';
							if ($b == "" && !$_POST) echo
							script("focus(qs('#form')['name']);");
							echo ($ac ? "<select name='Engine'>" . optionlist(array("" => "(" . lang(166) . ")") + $ac, $L["Engine"]) . "</select>" . on_help("getTarget(event).value", 1) . script("qsl('select').onchange = helpClose;") : ""), ' ', ($Xa && !preg_match("~sqlite|mssql~", $y) ? html_select("Collation", array("" => "(" . lang(95) . ")") + $Xa, $L["Collation"]) : ""), ' <input type="submit" value="', lang(14), '">
';
						}
						echo '
';
						if (support("columns")) {
							echo '<div class="scrollable">
<table cellspacing="0" id="edit-fields" class="nowrap">
';
							edit_fields($L["fields"], $Xa, "TABLE", $Bc);
							echo '</table>
', script("editFields();"), '</div>
<p>
', lang(40), ': <input type="number" name="Auto_increment" size="6" value="', h($L["Auto_increment"]), '">
', checkbox("defaults", 1, ($_POST ? $_POST["defaults"] : adminer_setting("defaults")), lang(167), "columnShow(this.checked, 5)", "jsonly"), (support("comment") ? checkbox("comments", 1, ($_POST ? $_POST["comments"] : adminer_setting("comments")), lang(39), "editingCommentsClick(this, true);", "jsonly") . ' <input name="Comment" value="' . h($L["Comment"]) . '" data-maxlength="' . (min_version(5.5) ? 2048 : 60) . '">' : ''), '<p>
<input type="submit" value="', lang(14), '">
';
						}
						echo '
';
						if ($b != "") {
							echo '<input type="submit" name="drop" value="', lang(121), '">', confirm(lang(168, $b));
						}
						if (support("partitioning")) {
							$Pe = preg_match('~RANGE|LIST~', $L["partition_by"]);
							print_fieldset("partition", lang(169), $L["partition_by"]);
							echo '<p>
', "<select name='partition_by'>" . optionlist(array("" => "") + $Oe, $L["partition_by"]) . "</select>" . on_help("getTarget(event).value.replace(/./, 'PARTITION BY \$&')", 1) . script("qsl('select').onchange = partitionByChange;"), '(<input name="partition" value="', h($L["partition"]), '">)
', lang(170), ': <input type="number" name="partitions" class="size', ($Pe || !$L["partition_by"] ? " hidden" : ""), '" value="', h($L["partitions"]), '">
<table cellspacing="0" id="partition-table"', ($Pe ? "" : " class='hidden'"), '>
<thead><tr><th>', lang(171), '<th>', lang(172), '</thead>
';
							foreach ($L["partition_names"] as $z => $X) {
								echo '<tr>', '<td><input name="partition_names[]" value="' . h($X) . '" autocapitalize="off">', ($z == count($L["partition_names"]) - 1 ? script("qsl('input').oninput = partitionNameChange;") : ''), '<td><input name="partition_values[]" value="' . h($L["partition_values"][$z]) . '">';
							}
							echo '</table>
</div></fieldset>
';
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["indexes"])) {
						$b = $_GET["indexes"];
						$bd = array("PRIMARY", "UNIQUE", "INDEX");
						$R = table_status($b, true);
						if (preg_match('~MyISAM|M?aria' . (min_version(5.6, '10.0.5') ? '|InnoDB' : '') . '~i', $R["Engine"])) $bd[] = "FULLTEXT";
						if (preg_match('~MyISAM|M?aria' . (min_version(5.7, '10.2.2') ? '|InnoDB' : '') . '~i', $R["Engine"])) $bd[] = "SPATIAL";
						$x = indexes($b);
						$ff = array();
						if ($y == "mongo") {
							$ff = $x["_id_"];
							unset($bd[0]);
							unset($x["_id_"]);
						}
						$L = $_POST;
						if ($_POST && !$m && !$_POST["add"] && !$_POST["drop_col"]) {
							$sa = array();
							foreach ($L["indexes"] as $w) {
								$E = $w["name"];
								if (in_array($w["type"], $bd)) {
									$e = array();
									$Cd = array();
									$Eb = array();
									$P = array();
									ksort($w["columns"]);
									foreach ($w["columns"] as $z => $d) {
										if ($d != "") {
											$Bd = $w["lengths"][$z];
											$Db = $w["descs"][$z];
											$P[] = idf_escape($d) . ($Bd ? "(" . (+$Bd) . ")" : "") . ($Db ? " DESC" : "");
											$e[] = $d;
											$Cd[] = ($Bd ? $Bd : null);
											$Eb[] = $Db;
										}
									}
									if ($e) {
										$kc = $x[$E];
										if ($kc) {
											ksort($kc["columns"]);
											ksort($kc["lengths"]);
											ksort($kc["descs"]);
											if ($w["type"] == $kc["type"] && array_values($kc["columns"]) === $e && (!$kc["lengths"] || array_values($kc["lengths"]) === $Cd) && array_values($kc["descs"]) === $Eb) {
												unset($x[$E]);
												continue;
											}
										}
										$sa[] = array($w["type"], $E, $P);
									}
								}
							}
							foreach ($x
								as $E => $kc) $sa[] = array($kc["type"], $E, "DROP");
							if (!$sa) redirect(ME . "table=" . urlencode($b));
							queries_redirect(ME . "table=" . urlencode($b), lang(173), alter_indexes($b, $sa));
						}
						page_header(lang(125), $m, array("table" => $b), h($b));
						$o = array_keys(fields($b));
						if ($_POST["add"]) {
							foreach ($L["indexes"] as $z => $w) {
								if ($w["columns"][count($w["columns"])] != "") $L["indexes"][$z]["columns"][] = "";
							}
							$w = end($L["indexes"]);
							if ($w["type"] || array_filter($w["columns"], 'strlen')) $L["indexes"][] = array("columns" => array(1 => ""));
						}
						if (!$L) {
							foreach ($x
								as $z => $w) {
								$x[$z]["name"] = $z;
								$x[$z]["columns"][] = "";
							}
							$x[] = array("columns" => array(1 => ""));
							$L["indexes"] = $x;
						}
						echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
<thead><tr>
<th id="label-type">', lang(174), '<th><input type="submit" class="wayoff">', lang(175), '<th id="label-name">', lang(176), '<th><noscript>', "<input type='image' class='icon' name='add[0]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.8.1") . "' alt='+' title='" . lang(102) . "'>", '</noscript>
</thead>
';
						if ($ff) {
							echo "<tr><td>PRIMARY<td>";
							foreach ($ff["columns"] as $z => $d) {
								echo
								select_input(" disabled", $o, $d), "<label><input disabled type='checkbox'>" . lang(48) . "</label> ";
							}
							echo "<td><td>\n";
						}
						$nd = 1;
						foreach ($L["indexes"] as $w) {
							if (!$_POST["drop_col"] || $nd != key($_POST["drop_col"])) {
								echo "<tr><td>" . html_select("indexes[$nd][type]", array(-1 => "") + $bd, $w["type"], ($nd == count($L["indexes"]) ? "indexesAddRow.call(this);" : 1), "label-type"), "<td>";
								ksort($w["columns"]);
								$t = 1;
								foreach ($w["columns"] as $z => $d) {
									echo "<span>" . select_input(" name='indexes[$nd][columns][$t]' title='" . lang(37) . "'", ($o ? array_combine($o, $o) : $o), $d, "partial(" . ($t == count($w["columns"]) ? "indexesAddColumn" : "indexesChangeColumn") . ", '" . js_escape($y == "sql" ? "" : $_GET["indexes"] . "_") . "')"), ($y == "sql" || $y == "mssql" ? "<input type='number' name='indexes[$nd][lengths][$t]' class='size' value='" . h($w["lengths"][$z]) . "' title='" . lang(100) . "'>" : ""), (support("descidx") ? checkbox("indexes[$nd][descs][$t]", 1, $w["descs"][$z], lang(48)) : ""), " </span>";
									$t++;
								}
								echo "<td><input name='indexes[$nd][name]' value='" . h($w["name"]) . "' autocapitalize='off' aria-labelledby='label-name'>\n", "<td><input type='image' class='icon' name='drop_col[$nd]' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=cross.gif&version=4.8.1") . "' alt='x' title='" . lang(105) . "'>" . script("qsl('input').onclick = partial(editingRemoveRow, 'indexes\$1[type]');");
							}
							$nd++;
						}
						echo '</table>
</div>
<p>
<input type="submit" value="', lang(14), '">
<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["database"])) {
						$L = $_POST;
						if ($_POST && !$m && !isset($_POST["add_x"])) {
							$E = trim($L["name"]);
							if ($_POST["drop"]) {
								$_GET["db"] = "";
								queries_redirect(remove_from_uri("db|database"), lang(177), drop_databases(array(DB)));
							} elseif (DB !== $E) {
								if (DB != "") {
									$_GET["db"] = $E;
									queries_redirect(preg_replace('~\bdb=[^&]*&~', '', ME) . "db=" . urlencode($E), lang(178), rename_database($E, $L["collation"]));
								} else {
									$j = explode("\n", str_replace("\r", "", $E));
									$og = true;
									$wd = "";
									foreach ($j
										as $k) {
										if (count($j) == 1 || $k != "") {
											if (!create_database($k, $L["collation"])) $og = false;
											$wd = $k;
										}
									}
									restart_session();
									set_session("dbs", null);
									queries_redirect(ME . "db=" . urlencode($wd), lang(179), $og);
								}
							} else {
								if (!$L["collation"]) redirect(substr(ME, 0, -1));
								query_redirect("ALTER DATABASE " . idf_escape($E) . (preg_match('~^[a-z0-9_]+$~i', $L["collation"]) ? " COLLATE $L[collation]" : ""), substr(ME, 0, -1), lang(180));
							}
						}
						page_header(DB != "" ? lang(56) : lang(109), $m, array(), h(DB));
						$Xa = collations();
						$E = DB;
						if ($_POST) $E = $L["name"];
						elseif (DB != "") $L["collation"] = db_collation(DB, $Xa);
						elseif ($y == "sql") {
							foreach (get_vals("SHOW GRANTS") as $Hc) {
								if (preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~', $Hc, $C) && $C[1]) {
									$E = stripcslashes(idf_unescape("`$C[2]`"));
									break;
								}
							}
						}
						echo '
<form action="" method="post">
<p>
', ($_POST["add_x"] || strpos($E, "\n") ? '<textarea id="name" name="name" rows="10" cols="40">' . h($E) . '</textarea><br>' : '<input name="name" id="name" value="' . h($E) . '" data-maxlength="64" autocapitalize="off">') . "\n" . ($Xa ? html_select("collation", array("" => "(" . lang(95) . ")") + $Xa, $L["collation"]) . doc_link(array('sql' => "charset-charsets.html", 'mariadb' => "supported-character-sets-and-collations/",)) : ""), script("focus(qs('#name'));"), '<input type="submit" value="', lang(14), '">
';
						if (DB != "") echo "<input type='submit' name='drop' value='" . lang(121) . "'>" . confirm(lang(168, DB)) . "\n";
						elseif (!$_POST["add_x"] && $_GET["db"] == "") echo "<input type='image' class='icon' name='add' src='" . h(preg_replace("~\\?.*~", "", ME) . "?file=plus.gif&version=4.8.1") . "' alt='+' title='" . lang(102) . "'>\n";
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["call"])) {
						$ca = ($_GET["name"] ? $_GET["name"] : $_GET["call"]);
						page_header(lang(181) . ": " . h($ca), $m);
						$Ff = routine($_GET["call"], (isset($_GET["callf"]) ? "FUNCTION" : "PROCEDURE"));
						$ad = array();
						$He = array();
						foreach ($Ff["fields"] as $t => $n) {
							if (substr($n["inout"], -3) == "OUT") $He[$t] = "@" . idf_escape($n["field"]) . " AS " . idf_escape($n["field"]);
							if (!$n["inout"] || substr($n["inout"], 0, 2) == "IN") $ad[] = $t;
						}
						if (!$m && $_POST) {
							$Ja = array();
							foreach ($Ff["fields"] as $z => $n) {
								if (in_array($z, $ad)) {
									$X = process_input($n);
									if ($X === false) $X = "''";
									if (isset($He[$z])) $g->query("SET @" . idf_escape($n["field"]) . " = $X");
								}
								$Ja[] = (isset($He[$z]) ? "@" . idf_escape($n["field"]) : $X);
							}
							$I = (isset($_GET["callf"]) ? "SELECT" : "CALL") . " " . table($ca) . "(" . implode(", ", $Ja) . ")";
							$hg = microtime(true);
							$J = $g->multi_query($I);
							$na = $g->affected_rows;
							echo $c->selectQuery($I, $hg, !$J);
							if (!$J) echo "<p class='error'>" . error() . "\n";
							else {
								$h = connect();
								if (is_object($h)) $h->select_db(DB);
								do {
									$J = $g->store_result();
									if (is_object($J)) select($J, $h);
									else
										echo "<p class='message'>" . lang(182, $na) . " <span class='time'>" . @date("H:i:s") . "</span>\n";
								} while ($g->next_result());
								if ($He) select($g->query("SELECT " . implode(", ", $He)));
							}
						}
						echo '
<form action="" method="post">
';
						if ($ad) {
							echo "<table cellspacing='0' class='layout'>\n";
							foreach ($ad
								as $z) {
								$n = $Ff["fields"][$z];
								$E = $n["field"];
								echo "<tr><th>" . $c->fieldName($n);
								$Y = $_POST["fields"][$E];
								if ($Y != "") {
									if ($n["type"] == "enum") $Y = +$Y;
									if ($n["type"] == "set") $Y = array_sum($Y);
								}
								input($n, $Y, (string)$_POST["function"][$E]);
								echo "\n";
							}
							echo "</table>\n";
						}
						echo '<p>
<input type="submit" value="', lang(181), '">
<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["foreign"])) {
						$b = $_GET["foreign"];
						$E = $_GET["name"];
						$L = $_POST;
						if ($_POST && !$m && !$_POST["add"] && !$_POST["change"] && !$_POST["change-js"]) {
							$D = ($_POST["drop"] ? lang(183) : ($E != "" ? lang(184) : lang(185)));
							$B = ME . "table=" . urlencode($b);
							if (!$_POST["drop"]) {
								$L["source"] = array_filter($L["source"], 'strlen');
								ksort($L["source"]);
								$Ag = array();
								foreach ($L["source"] as $z => $X) $Ag[$z] = $L["target"][$z];
								$L["target"] = $Ag;
							}
							if ($y == "sqlite") queries_redirect($B, $D, recreate_table($b, $b, array(), array(), array(" $E" => ($_POST["drop"] ? "" : " " . format_foreign_key($L)))));
							else {
								$sa = "ALTER TABLE " . table($b);
								$Lb = "\nDROP " . ($y == "sql" ? "FOREIGN KEY " : "CONSTRAINT ") . idf_escape($E);
								if ($_POST["drop"]) query_redirect($sa . $Lb, $B, $D);
								else {
									query_redirect($sa . ($E != "" ? "$Lb," : "") . "\nADD" . format_foreign_key($L), $B, $D);
									$m = lang(186) . "<br>$m";
								}
							}
						}
						page_header(lang(187), $m, array("table" => $b), h($b));
						if ($_POST) {
							ksort($L["source"]);
							if ($_POST["add"]) $L["source"][] = "";
							elseif ($_POST["change"] || $_POST["change-js"]) $L["target"] = array();
						} elseif ($E != "") {
							$Bc = foreign_keys($b);
							$L = $Bc[$E];
							$L["source"][] = "";
						} else {
							$L["table"] = $b;
							$L["source"] = array("");
						}
						echo '
<form action="" method="post">
';
						$bg = array_keys(fields($b));
						if ($L["db"] != "") $g->select_db($L["db"]);
						if ($L["ns"] != "") set_schema($L["ns"]);
						$uf = array_keys(array_filter(table_status('', true), 'fk_support'));
						$Ag = array_keys(fields(in_array($L["table"], $uf) ? $L["table"] : reset($uf)));
						$se = "this.form['change-js'].value = '1'; this.form.submit();";
						echo "<p>" . lang(188) . ": " . html_select("table", $uf, $L["table"], $se) . "\n";
						if ($y == "pgsql") echo
						lang(189) . ": " . html_select("ns", $c->schemas(), $L["ns"] != "" ? $L["ns"] : $_GET["ns"], $se);
						elseif ($y != "sqlite") {
							$xb = array();
							foreach ($c->databases() as $k) {
								if (!information_schema($k)) $xb[] = $k;
							}
							echo
							lang(65) . ": " . html_select("db", $xb, $L["db"] != "" ? $L["db"] : $_GET["db"], $se);
						}
						echo '<input type="hidden" name="change-js" value="">
<noscript><p><input type="submit" name="change" value="', lang(190), '"></noscript>
<table cellspacing="0">
<thead><tr><th id="label-source">', lang(127), '<th id="label-target">', lang(128), '</thead>
';
						$nd = 0;
						foreach ($L["source"] as $z => $X) {
							echo "<tr>", "<td>" . html_select("source[" . (+$z) . "]", array(-1 => "") + $bg, $X, ($nd == count($L["source"]) - 1 ? "foreignAddRow.call(this);" : 1), "label-source"), "<td>" . html_select("target[" . (+$z) . "]", $Ag, $L["target"][$z], 1, "label-target");
							$nd++;
						}
						echo '</table>
<p>
', lang(97), ': ', html_select("on_delete", array(-1 => "") + explode("|", $re), $L["on_delete"]), ' ', lang(96), ': ', html_select("on_update", array(-1 => "") + explode("|", $re), $L["on_update"]), doc_link(array('sql' => "innodb-foreign-key-constraints.html", 'mariadb' => "foreign-keys/",)), '<p>
<input type="submit" value="', lang(14), '">
<noscript><p><input type="submit" name="add" value="', lang(191), '"></noscript>
';
						if ($E != "") {
							echo '<input type="submit" name="drop" value="', lang(121), '">', confirm(lang(168, $E));
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["view"])) {
						$b = $_GET["view"];
						$L = $_POST;
						$Fe = "VIEW";
						if ($y == "pgsql" && $b != "") {
							$ig = table_status($b);
							$Fe = strtoupper($ig["Engine"]);
						}
						if ($_POST && !$m) {
							$E = trim($L["name"]);
							$ua = " AS\n$L[select]";
							$B = ME . "table=" . urlencode($E);
							$D = lang(192);
							$U = ($_POST["materialized"] ? "MATERIALIZED VIEW" : "VIEW");
							if (!$_POST["drop"] && $b == $E && $y != "sqlite" && $U == "VIEW" && $Fe == "VIEW") query_redirect(($y == "mssql" ? "ALTER" : "CREATE OR REPLACE") . " VIEW " . table($E) . $ua, $B, $D);
							else {
								$Cg = $E . "_adminer_" . uniqid();
								drop_create("DROP $Fe " . table($b), "CREATE $U " . table($E) . $ua, "DROP $U " . table($E), "CREATE $U " . table($Cg) . $ua, "DROP $U " . table($Cg), ($_POST["drop"] ? substr(ME, 0, -1) : $B), lang(193), $D, lang(194), $b, $E);
							}
						}
						if (!$_POST && $b != "") {
							$L = view($b);
							$L["name"] = $b;
							$L["materialized"] = ($Fe != "VIEW");
							if (!$m) $m = error();
						}
						page_header(($b != "" ? lang(32) : lang(195)), $m, array("table" => $b), h($b));
						echo '
<form action="" method="post">
<p>', lang(176), ': <input name="name" value="', h($L["name"]), '" data-maxlength="64" autocapitalize="off">
', (support("materializedview") ? " " . checkbox("materialized", 1, $L["materialized"], lang(122)) : ""), '<p>';
						textarea("select", $L["select"]);
						echo '<p>
<input type="submit" value="', lang(14), '">
';
						if ($b != "") {
							echo '<input type="submit" name="drop" value="', lang(121), '">', confirm(lang(168, $b));
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["event"])) {
						$aa = $_GET["event"];
						$gd = array("YEAR", "QUARTER", "MONTH", "DAY", "HOUR", "MINUTE", "WEEK", "SECOND", "YEAR_MONTH", "DAY_HOUR", "DAY_MINUTE", "DAY_SECOND", "HOUR_MINUTE", "HOUR_SECOND", "MINUTE_SECOND");
						$jg = array("ENABLED" => "ENABLE", "DISABLED" => "DISABLE", "SLAVESIDE_DISABLED" => "DISABLE ON SLAVE");
						$L = $_POST;
						if ($_POST && !$m) {
							if ($_POST["drop"]) query_redirect("DROP EVENT " . idf_escape($aa), substr(ME, 0, -1), lang(196));
							elseif (in_array($L["INTERVAL_FIELD"], $gd) && isset($jg[$L["STATUS"]])) {
								$Jf = "\nON SCHEDULE " . ($L["INTERVAL_VALUE"] ? "EVERY " . q($L["INTERVAL_VALUE"]) . " $L[INTERVAL_FIELD]" . ($L["STARTS"] ? " STARTS " . q($L["STARTS"]) : "") . ($L["ENDS"] ? " ENDS " . q($L["ENDS"]) : "") : "AT " . q($L["STARTS"])) . " ON COMPLETION" . ($L["ON_COMPLETION"] ? "" : " NOT") . " PRESERVE";
								queries_redirect(substr(ME, 0, -1), ($aa != "" ? lang(197) : lang(198)), queries(($aa != "" ? "ALTER EVENT " . idf_escape($aa) . $Jf . ($aa != $L["EVENT_NAME"] ? "\nRENAME TO " . idf_escape($L["EVENT_NAME"]) : "") : "CREATE EVENT " . idf_escape($L["EVENT_NAME"]) . $Jf) . "\n" . $jg[$L["STATUS"]] . " COMMENT " . q($L["EVENT_COMMENT"]) . rtrim(" DO\n$L[EVENT_DEFINITION]", ";") . ";"));
							}
						}
						page_header(($aa != "" ? lang(199) . ": " . h($aa) : lang(200)), $m);
						if (!$L && $aa != "") {
							$M = get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = " . q(DB) . " AND EVENT_NAME = " . q($aa));
							$L = reset($M);
						}
						echo '
<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>', lang(176), '<td><input name="EVENT_NAME" value="', h($L["EVENT_NAME"]), '" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">', lang(201), '<td><input name="STARTS" value="', h("$L[EXECUTE_AT]$L[STARTS]"), '">
<tr><th title="datetime">', lang(202), '<td><input name="ENDS" value="', h($L["ENDS"]), '">
<tr><th>', lang(203), '<td><input type="number" name="INTERVAL_VALUE" value="', h($L["INTERVAL_VALUE"]), '" class="size"> ', html_select("INTERVAL_FIELD", $gd, $L["INTERVAL_FIELD"]), '<tr><th>', lang(112), '<td>', html_select("STATUS", $jg, $L["STATUS"]), '<tr><th>', lang(39), '<td><input name="EVENT_COMMENT" value="', h($L["EVENT_COMMENT"]), '" data-maxlength="64">
<tr><th><td>', checkbox("ON_COMPLETION", "PRESERVE", $L["ON_COMPLETION"] == "PRESERVE", lang(204)), '</table>
<p>';
						textarea("EVENT_DEFINITION", $L["EVENT_DEFINITION"]);
						echo '<p>
<input type="submit" value="', lang(14), '">
';
						if ($aa != "") {
							echo '<input type="submit" name="drop" value="', lang(121), '">', confirm(lang(168, $aa));
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["procedure"])) {
						$ca = ($_GET["name"] ? $_GET["name"] : $_GET["procedure"]);
						$Ff = (isset($_GET["function"]) ? "FUNCTION" : "PROCEDURE");
						$L = $_POST;
						$L["fields"] = (array)$L["fields"];
						if ($_POST && !process_fields($L["fields"]) && !$m) {
							$Ce = routine($_GET["procedure"], $Ff);
							$Cg = "$L[name]_adminer_" . uniqid();
							drop_create("DROP $Ff " . routine_id($ca, $Ce), create_routine($Ff, $L), "DROP $Ff " . routine_id($L["name"], $L), create_routine($Ff, array("name" => $Cg) + $L), "DROP $Ff " . routine_id($Cg, $L), substr(ME, 0, -1), lang(205), lang(206), lang(207), $ca, $L["name"]);
						}
						page_header(($ca != "" ? (isset($_GET["function"]) ? lang(208) : lang(209)) . ": " . h($ca) : (isset($_GET["function"]) ? lang(210) : lang(211))), $m);
						if (!$_POST && $ca != "") {
							$L = routine($_GET["procedure"], $Ff);
							$L["name"] = $ca;
						}
						$Xa = get_vals("SHOW CHARACTER SET");
						sort($Xa);
						$Gf = routine_languages();
						echo '
<form action="" method="post" id="form">
<p>', lang(176), ': <input name="name" value="', h($L["name"]), '" data-maxlength="64" autocapitalize="off">
', ($Gf ? lang(19) . ": " . html_select("language", $Gf, $L["language"]) . "\n" : ""), '<input type="submit" value="', lang(14), '">
<div class="scrollable">
<table cellspacing="0" class="nowrap">
';
						edit_fields($L["fields"], $Xa, $Ff);
						if (isset($_GET["function"])) {
							echo "<tr><td>" . lang(212);
							edit_type("returns", $L["returns"], $Xa, array(), ($y == "pgsql" ? array("void", "trigger") : array()));
						}
						echo '</table>
', script("editFields();"), '</div>
<p>';
						textarea("definition", $L["definition"]);
						echo '<p>
<input type="submit" value="', lang(14), '">
';
						if ($ca != "") {
							echo '<input type="submit" name="drop" value="', lang(121), '">', confirm(lang(168, $ca));
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["trigger"])) {
						$b = $_GET["trigger"];
						$E = $_GET["name"];
						$Wg = trigger_options();
						$L = (array)trigger($E, $b) + array("Trigger" => $b . "_bi");
						if ($_POST) {
							if (!$m && in_array($_POST["Timing"], $Wg["Timing"]) && in_array($_POST["Event"], $Wg["Event"]) && in_array($_POST["Type"], $Wg["Type"])) {
								$qe = " ON " . table($b);
								$Lb = "DROP TRIGGER " . idf_escape($E) . ($y == "pgsql" ? $qe : "");
								$B = ME . "table=" . urlencode($b);
								if ($_POST["drop"]) query_redirect($Lb, $B, lang(213));
								else {
									if ($E != "") queries($Lb);
									queries_redirect($B, ($E != "" ? lang(214) : lang(215)), queries(create_trigger($qe, $_POST)));
									if ($E != "") queries(create_trigger($qe, $L + array("Type" => reset($Wg["Type"]))));
								}
							}
							$L = $_POST;
						}
						page_header(($E != "" ? lang(216) . ": " . h($E) : lang(217)), $m, array("table" => $b));
						echo '
<form action="" method="post" id="form">
<table cellspacing="0" class="layout">
<tr><th>', lang(218), '<td>', html_select("Timing", $Wg["Timing"], $L["Timing"], "triggerChange(/^" . preg_quote($b, "/") . "_[ba][iud]$/, '" . js_escape($b) . "', this.form);"), '<tr><th>', lang(219), '<td>', html_select("Event", $Wg["Event"], $L["Event"], "this.form['Timing'].onchange();"), (in_array("UPDATE OF", $Wg["Event"]) ? " <input name='Of' value='" . h($L["Of"]) . "' class='hidden'>" : ""), '<tr><th>', lang(38), '<td>', html_select("Type", $Wg["Type"], $L["Type"]), '</table>
<p>', lang(176), ': <input name="Trigger" value="', h($L["Trigger"]), '" data-maxlength="64" autocapitalize="off">
', script("qs('#form')['Timing'].onchange();"), '<p>';
						textarea("Statement", $L["Statement"]);
						echo '<p>
<input type="submit" value="', lang(14), '">
';
						if ($E != "") {
							echo '<input type="submit" name="drop" value="', lang(121), '">', confirm(lang(168, $E));
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["user"])) {
						$ea = $_GET["user"];
						$jf = array("" => array("All privileges" => ""));
						foreach (get_rows("SHOW PRIVILEGES") as $L) {
							foreach (explode(",", ($L["Privilege"] == "Grant option" ? "" : $L["Context"])) as $ib) $jf[$ib][$L["Privilege"]] = $L["Comment"];
						}
						$jf["Server Admin"] += $jf["File access on server"];
						$jf["Databases"]["Create routine"] = $jf["Procedures"]["Create routine"];
						unset($jf["Procedures"]["Create routine"]);
						$jf["Columns"] = array();
						foreach (array("Select", "Insert", "Update", "References") as $X) $jf["Columns"][$X] = $jf["Tables"][$X];
						unset($jf["Server Admin"]["Usage"]);
						foreach ($jf["Tables"] as $z => $X) unset($jf["Databases"][$z]);
						$be = array();
						if ($_POST) {
							foreach ($_POST["objects"] as $z => $X) $be[$X] = (array)$be[$X] + (array)$_POST["grants"][$z];
						}
						$Ic = array();
						$oe = "";
						if (isset($_GET["host"]) && ($J = $g->query("SHOW GRANTS FOR " . q($ea) . "@" . q($_GET["host"])))) {
							while ($L = $J->fetch_row()) {
								if (preg_match('~GRANT (.*) ON (.*) TO ~', $L[0], $C) && preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~', $C[1], $Jd, PREG_SET_ORDER)) {
									foreach ($Jd
										as $X) {
										if ($X[1] != "USAGE") $Ic["$C[2]$X[2]"][$X[1]] = true;
										if (preg_match('~ WITH GRANT OPTION~', $L[0])) $Ic["$C[2]$X[2]"]["GRANT OPTION"] = true;
									}
								}
								if (preg_match("~ IDENTIFIED BY PASSWORD '([^']+)~", $L[0], $C)) $oe = $C[1];
							}
						}
						if ($_POST && !$m) {
							$pe = (isset($_GET["host"]) ? q($ea) . "@" . q($_GET["host"]) : "''");
							if ($_POST["drop"]) query_redirect("DROP USER $pe", ME . "privileges=", lang(220));
							else {
								$de = q($_POST["user"]) . "@" . q($_POST["host"]);
								$Se = $_POST["pass"];
								if ($Se != '' && !$_POST["hashed"] && !min_version(8)) {
									$Se = $g->result("SELECT PASSWORD(" . q($Se) . ")");
									$m = !$Se;
								}
								$mb = false;
								if (!$m) {
									if ($pe != $de) {
										$mb = queries((min_version(5) ? "CREATE USER" : "GRANT USAGE ON *.* TO") . " $de IDENTIFIED BY " . (min_version(8) ? "" : "PASSWORD ") . q($Se));
										$m = !$mb;
									} elseif ($Se != $oe) queries("SET PASSWORD FOR $de = " . q($Se));
								}
								if (!$m) {
									$Cf = array();
									foreach ($be
										as $je => $Hc) {
										if (isset($_GET["grant"])) $Hc = array_filter($Hc);
										$Hc = array_keys($Hc);
										if (isset($_GET["grant"])) $Cf = array_diff(array_keys(array_filter($be[$je], 'strlen')), $Hc);
										elseif ($pe == $de) {
											$me = array_keys((array)$Ic[$je]);
											$Cf = array_diff($me, $Hc);
											$Hc = array_diff($Hc, $me);
											unset($Ic[$je]);
										}
										if (preg_match('~^(.+)\s*(\(.*\))?$~U', $je, $C) && (!grant("REVOKE", $Cf, $C[2], " ON $C[1] FROM $de") || !grant("GRANT", $Hc, $C[2], " ON $C[1] TO $de"))) {
											$m = true;
											break;
										}
									}
								}
								if (!$m && isset($_GET["host"])) {
									if ($pe != $de) queries("DROP USER $pe");
									elseif (!isset($_GET["grant"])) {
										foreach ($Ic
											as $je => $Cf) {
											if (preg_match('~^(.+)(\(.*\))?$~U', $je, $C)) grant("REVOKE", array_keys($Cf), $C[2], " ON $C[1] FROM $de");
										}
									}
								}
								queries_redirect(ME . "privileges=", (isset($_GET["host"]) ? lang(221) : lang(222)), !$m);
								if ($mb) $g->query("DROP USER $de");
							}
						}
						page_header((isset($_GET["host"]) ? lang(24) . ": " . h("$ea@$_GET[host]") : lang(139)), $m, array("privileges" => array('', lang(60))));
						if ($_POST) {
							$L = $_POST;
							$Ic = $be;
						} else {
							$L = $_GET + array("host" => $g->result("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));
							$L["pass"] = $oe;
							if ($oe != "") $L["hashed"] = true;
							$Ic[(DB == "" || $Ic ? "" : idf_escape(addcslashes(DB, "%_\\"))) . ".*"] = array();
						}
						echo '<form action="" method="post">
<table cellspacing="0" class="layout">
<tr><th>', lang(23), '<td><input name="host" data-maxlength="60" value="', h($L["host"]), '" autocapitalize="off">
<tr><th>', lang(24), '<td><input name="user" data-maxlength="80" value="', h($L["user"]), '" autocapitalize="off">
<tr><th>', lang(25), '<td><input name="pass" id="pass" value="', h($L["pass"]), '" autocomplete="new-password">
';
						if (!$L["hashed"]) echo
						script("typePassword(qs('#pass'));");
						echo (min_version(8) ? "" : checkbox("hashed", 1, $L["hashed"], lang(223), "typePassword(this.form['pass'], this.checked);")), '</table>

';
						echo "<table cellspacing='0'>\n", "<thead><tr><th colspan='2'>" . lang(60) . doc_link(array('sql' => "grant.html#priv_level"));
						$t = 0;
						foreach ($Ic
							as $je => $Hc) {
							echo '<th>' . ($je != "*.*" ? "<input name='objects[$t]' value='" . h($je) . "' size='10' autocapitalize='off'>" : "<input type='hidden' name='objects[$t]' value='*.*' size='10'>*.*");
							$t++;
						}
						echo "</thead>\n";
						foreach (array("" => "", "Server Admin" => lang(23), "Databases" => lang(26), "Tables" => lang(124), "Columns" => lang(37), "Procedures" => lang(224),) as $ib => $Db) {
							foreach ((array)$jf[$ib] as $if => $bb) {
								echo "<tr" . odd() . "><td" . ($Db ? ">$Db<td" : " colspan='2'") . ' lang="en" title="' . h($bb) . '">' . h($if);
								$t = 0;
								foreach ($Ic
									as $je => $Hc) {
									$E = "'grants[$t][" . h(strtoupper($if)) . "]'";
									$Y = $Hc[strtoupper($if)];
									if ($ib == "Server Admin" && $je != (isset($Ic["*.*"]) ? "*.*" : ".*")) echo "<td>";
									elseif (isset($_GET["grant"])) echo "<td><select name=$E><option><option value='1'" . ($Y ? " selected" : "") . ">" . lang(225) . "<option value='0'" . ($Y == "0" ? " selected" : "") . ">" . lang(226) . "</select>";
									else {
										echo "<td align='center'><label class='block'>", "<input type='checkbox' name=$E value='1'" . ($Y ? " checked" : "") . ($if == "All privileges" ? " id='grants-$t-all'>" : ">" . ($if == "Grant option" ? "" : script("qsl('input').onclick = function () { if (this.checked) formUncheck('grants-$t-all'); };"))), "</label>";
									}
									$t++;
								}
							}
						}
						echo "</table>\n", '<p>
<input type="submit" value="', lang(14), '">
';
						if (isset($_GET["host"])) {
							echo '<input type="submit" name="drop" value="', lang(121), '">', confirm(lang(168, "$ea@$_GET[host]"));
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
';
					} elseif (isset($_GET["processlist"])) {
						if (support("kill")) {
							if ($_POST && !$m) {
								$rd = 0;
								foreach ((array)$_POST["kill"] as $X) {
									if (kill_process($X)) $rd++;
								}
								queries_redirect(ME . "processlist=", lang(227, $rd), $rd || !$_POST["kill"]);
							}
						}
						page_header(lang(110), $m);
						echo '
<form action="" method="post">
<div class="scrollable">
<table cellspacing="0" class="nowrap checkable">
', script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});");
						$t = -1;
						foreach (process_list() as $t => $L) {
							if (!$t) {
								echo "<thead><tr lang='en'>" . (support("kill") ? "<th>" : "");
								foreach ($L
									as $z => $X) echo "<th>$z" . doc_link(array('sql' => "show-processlist.html#processlist_" . strtolower($z),));
								echo "</thead>\n";
							}
							echo "<tr" . odd() . ">" . (support("kill") ? "<td>" . checkbox("kill[]", $L[$y == "sql" ? "Id" : "pid"], 0) : "");
							foreach ($L
								as $z => $X) echo "<td>" . (($y == "sql" && $z == "Info" && preg_match("~Query|Killed~", $L["Command"]) && $X != "") || ($y == "pgsql" && $z == "current_query" && $X != "<IDLE>") || ($y == "oracle" && $z == "sql_text" && $X != "") ? "<code class='jush-$y'>" . shorten_utf8($X, 100, "</code>") . ' <a href="' . h(ME . ($L["db"] != "" ? "db=" . urlencode($L["db"]) . "&" : "") . "sql=" . urlencode($X)) . '">' . lang(228) . '</a>' : h($X));
							echo "\n";
						}
						echo '</table>
</div>
<p>
';
						if (support("kill")) {
							echo ($t + 1) . "/" . lang(229, max_connections()), "<p><input type='submit' value='" . lang(230) . "'>\n";
						}
						echo '<input type="hidden" name="token" value="', $T, '">
</form>
', script("tableCheck();");
					} elseif (isset($_GET["select"])) {
						$b = $_GET["select"];
						$R = table_status1($b);
						$x = indexes($b);
						$o = fields($b);
						$Bc = column_foreign_keys($b);
						$le = $R["Oid"];
						parse_str($_COOKIE["adminer_import"], $ma);
						$Df = array();
						$e = array();
						$Fg = null;
						foreach ($o
							as $z => $n) {
							$E = $c->fieldName($n);
							if (isset($n["privileges"]["select"]) && $E != "") {
								$e[$z] = html_entity_decode(strip_tags($E), ENT_QUOTES);
								if (is_shortable($n)) $Fg = $c->selectLengthProcess();
							}
							$Df += $n["privileges"];
						}
						list($N, $s) = $c->selectColumnsProcess($e, $x);
						$kd = count($s) < count($N);
						$Z = $c->selectSearchProcess($o, $x);
						$ze = $c->selectOrderProcess($o, $x);
						$_ = $c->selectLimitProcess();
						if ($_GET["val"] && is_ajax()) {
							header("Content-Type: text/plain; charset=utf-8");
							foreach ($_GET["val"] as $eh => $L) {
								$ua = convert_field($o[key($L)]);
								$N = array($ua ? $ua : idf_escape(key($L)));
								$Z[] = where_check($eh, $o);
								$K = $l->select($b, $N, $Z, $N);
								if ($K) echo
								reset($K->fetch_row());
							}
							exit;
						}
						$ff = $gh = null;
						foreach ($x
							as $w) {
							if ($w["type"] == "PRIMARY") {
								$ff = array_flip($w["columns"]);
								$gh = ($N ? $ff : array());
								foreach ($gh
									as $z => $X) {
									if (in_array(idf_escape($z), $N)) unset($gh[$z]);
								}
								break;
							}
						}
						if ($le && !$ff) {
							$ff = $gh = array($le => 0);
							$x[] = array("type" => "PRIMARY", "columns" => array($le));
						}
						if ($_POST && !$m) {
							$Bh = $Z;
							if (!$_POST["all"] && is_array($_POST["check"])) {
								$Oa = array();
								foreach ($_POST["check"] as $Ma) $Oa[] = where_check($Ma, $o);
								$Bh[] = "((" . implode(") OR (", $Oa) . "))";
							}
							$Bh = ($Bh ? "\nWHERE " . implode(" AND ", $Bh) : "");
							if ($_POST["export"]) {
								cookie("adminer_import", "output=" . urlencode($_POST["output"]) . "&format=" . urlencode($_POST["format"]));
								dump_headers($b);
								$c->dumpTable($b, "");
								$Fc = ($N ? implode(", ", $N) : "*") . convert_fields($e, $o, $N) . "\nFROM " . table($b);
								$Kc = ($s && $kd ? "\nGROUP BY " . implode(", ", $s) : "") . ($ze ? "\nORDER BY " . implode(", ", $ze) : "");
								if (!is_array($_POST["check"]) || $ff) $I = "SELECT $Fc$Bh$Kc";
								else {
									$ch = array();
									foreach ($_POST["check"] as $X) $ch[] = "(SELECT" . limit($Fc, "\nWHERE " . ($Z ? implode(" AND ", $Z) . " AND " : "") . where_check($X, $o) . $Kc, 1) . ")";
									$I = implode(" UNION ALL ", $ch);
								}
								$c->dumpData($b, "table", $I);
								exit;
							}
							if (!$c->selectEmailProcess($Z, $Bc)) {
								if ($_POST["save"] || $_POST["delete"]) {
									$J = true;
									$na = 0;
									$P = array();
									if (!$_POST["delete"]) {
										foreach ($e
											as $E => $X) {
											$X = process_input($o[$E]);
											if ($X !== null && ($_POST["clone"] || $X !== false)) $P[idf_escape($E)] = ($X !== false ? $X : idf_escape($E));
										}
									}
									if ($_POST["delete"] || $P) {
										if ($_POST["clone"]) $I = "INTO " . table($b) . " (" . implode(", ", array_keys($P)) . ")\nSELECT " . implode(", ", $P) . "\nFROM " . table($b);
										if ($_POST["all"] || ($ff && is_array($_POST["check"])) || $kd) {
											$J = ($_POST["delete"] ? $l->delete($b, $Bh) : ($_POST["clone"] ? queries("INSERT $I$Bh") : $l->update($b, $P, $Bh)));
											$na = $g->affected_rows;
										} else {
											foreach ((array)$_POST["check"] as $X) {
												$Ah = "\nWHERE " . ($Z ? implode(" AND ", $Z) . " AND " : "") . where_check($X, $o);
												$J = ($_POST["delete"] ? $l->delete($b, $Ah, 1) : ($_POST["clone"] ? queries("INSERT" . limit1($b, $I, $Ah)) : $l->update($b, $P, $Ah, 1)));
												if (!$J) break;
												$na += $g->affected_rows;
											}
										}
									}
									$D = lang(231, $na);
									if ($_POST["clone"] && $J && $na == 1) {
										$xd = last_id();
										if ($xd) $D = lang(161, " $xd");
									}
									queries_redirect(remove_from_uri($_POST["all"] && $_POST["delete"] ? "page" : ""), $D, $J);
									if (!$_POST["delete"]) {
										edit_form($b, $o, (array)$_POST["fields"], !$_POST["clone"]);
										page_footer();
										exit;
									}
								} elseif (!$_POST["import"]) {
									if (!$_POST["val"]) $m = lang(232);
									else {
										$J = true;
										$na = 0;
										foreach ($_POST["val"] as $eh => $L) {
											$P = array();
											foreach ($L
												as $z => $X) {
												$z = bracket_escape($z, 1);
												$P[idf_escape($z)] = (preg_match('~char|text~', $o[$z]["type"]) || $X != "" ? $c->processInput($o[$z], $X) : "NULL");
											}
											$J = $l->update($b, $P, " WHERE " . ($Z ? implode(" AND ", $Z) . " AND " : "") . where_check($eh, $o), !$kd && !$ff, " ");
											if (!$J) break;
											$na += $g->affected_rows;
										}
										queries_redirect(remove_from_uri(), lang(231, $na), $J);
									}
								} elseif (!is_string($uc = get_file("csv_file", true))) $m = upload_error($uc);
								elseif (!preg_match('~~u', $uc)) $m = lang(233);
								else {
									cookie("adminer_import", "output=" . urlencode($ma["output"]) . "&format=" . urlencode($_POST["separator"]));
									$J = true;
									$Ya = array_keys($o);
									preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~', $uc, $Jd);
									$na = count($Jd[0]);
									$l->begin();
									$Rf = ($_POST["separator"] == "csv" ? "," : ($_POST["separator"] == "tsv" ? "\t" : ";"));
									$M = array();
									foreach ($Jd[0] as $z => $X) {
										preg_match_all("~((?>\"[^\"]*\")+|[^$Rf]*)$Rf~", $X . $Rf, $Kd);
										if (!$z && !array_diff($Kd[1], $Ya)) {
											$Ya = $Kd[1];
											$na--;
										} else {
											$P = array();
											foreach ($Kd[1] as $t => $Ua) $P[idf_escape($Ya[$t])] = ($Ua == "" && $o[$Ya[$t]]["null"] ? "NULL" : q(str_replace('""', '"', preg_replace('~^"|"$~', '', $Ua))));
											$M[] = $P;
										}
									}
									$J = (!$M || $l->insertUpdate($b, $M, $ff));
									if ($J) $J = $l->commit();
									queries_redirect(remove_from_uri("page"), lang(234, $na), $J);
									$l->rollback();
								}
							}
						}
						$ug = $c->tableName($R);
						if (is_ajax()) {
							page_headers();
							ob_start();
						} else
							page_header(lang(42) . ": $ug", $m);
						$P = null;
						if (isset($Df["insert"]) || !support("table")) {
							$P = "";
							foreach ((array)$_GET["where"] as $X) {
								if ($Bc[$X["col"]] && count($Bc[$X["col"]]) == 1 && ($X["op"] == "=" || (!$X["op"] && !preg_match('~[_%]~', $X["val"])))) $P .= "&set" . urlencode("[" . bracket_escape($X["col"]) . "]") . "=" . urlencode($X["val"]);
							}
						}
						$c->selectLinks($R, $P);
						if (!$e && support("table")) echo "<p class='error'>" . lang(235) . ($o ? "." : ": " . error()) . "\n";
						else {
							echo "<form action='' id='form'>\n", "<div style='display: none;'>";
							hidden_fields_get();
							echo (DB != "" ? '<input type="hidden" name="db" value="' . h(DB) . '">' . (isset($_GET["ns"]) ? '<input type="hidden" name="ns" value="' . h($_GET["ns"]) . '">' : "") : "");
							echo '<input type="hidden" name="select" value="' . h($b) . '">', "</div>\n";
							$c->selectColumnsPrint($N, $e);
							$c->selectSearchPrint($Z, $e, $x);
							$c->selectOrderPrint($ze, $e, $x);
							$c->selectLimitPrint($_);
							$c->selectLengthPrint($Fg);
							$c->selectActionPrint($x);
							echo "</form>\n";
							$F = $_GET["page"];
							if ($F == "last") {
								$Ec = $g->result(count_rows($b, $Z, $kd, $s));
								$F = floor(max(0, $Ec - 1) / $_);
							}
							$Mf = $N;
							$Jc = $s;
							if (!$Mf) {
								$Mf[] = "*";
								$jb = convert_fields($e, $o, $N);
								if ($jb) $Mf[] = substr($jb, 2);
							}
							foreach ($N
								as $z => $X) {
								$n = $o[idf_unescape($X)];
								if ($n && ($ua = convert_field($n))) $Mf[$z] = "$ua AS $X";
							}
							if (!$kd && $gh) {
								foreach ($gh
									as $z => $X) {
									$Mf[] = idf_escape($z);
									if ($Jc) $Jc[] = idf_escape($z);
								}
							}
							$J = $l->select($b, $Mf, $Z, $Jc, $ze, $_, $F, true);
							if (!$J) echo "<p class='error'>" . error() . "\n";
							else {
								if ($y == "mssql" && $F) $J->seek($_ * $F);
								$Xb = array();
								echo "<form action='' method='post' enctype='multipart/form-data'>\n";
								$M = array();
								while ($L = $J->fetch_assoc()) {
									if ($F && $y == "oracle") unset($L["RNUM"]);
									$M[] = $L;
								}
								if ($_GET["page"] != "last" && $_ != "" && $s && $kd && $y == "sql") $Ec = $g->result(" SELECT FOUND_ROWS()");
								if (!$M) echo "<p class='message'>" . lang(12) . "\n";
								else {
									$Ba = $c->backwardKeys($b, $ug);
									echo "<div class='scrollable'>", "<table id='table' cellspacing='0' class='nowrap checkable'>", script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"), "<thead><tr>" . (!$s && $N ? "" : "<td><input type='checkbox' id='all-page' class='jsonly'>" . script("qs('#all-page').onclick = partial(formCheck, /check/);", "") . " <a href='" . h($_GET["modify"] ? remove_from_uri("modify") : $_SERVER["REQUEST_URI"] . "&modify=1") . "'>" . lang(236) . "</a>");
									$ae = array();
									$Gc = array();
									reset($N);
									$rf = 1;
									foreach ($M[0] as $z => $X) {
										if (!isset($gh[$z])) {
											$X = $_GET["columns"][key($N)];
											$n = $o[$N ? ($X ? $X["col"] : current($N)) : $z];
											$E = ($n ? $c->fieldName($n, $rf) : ($X["fun"] ? "*" : $z));
											if ($E != "") {
												$rf++;
												$ae[$z] = $E;
												$d = idf_escape($z);
												$Wc = remove_from_uri('(order|desc)[^=]*|page') . '&order%5B0%5D=' . urlencode($z);
												$Db = "&desc%5B0%5D=1";
												echo "<th id='th[" . h(bracket_escape($z)) . "]'>" . script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});", ""), '<a href="' . h($Wc . ($ze[0] == $d || $ze[0] == $z || (!$ze && $kd && $s[0] == $d) ? $Db : '')) . '">';
												echo
												apply_sql_function($X["fun"], $E) . "</a>";
												echo "<span class='column hidden'>", "<a href='" . h($Wc . $Db) . "' title='" . lang(48) . "' class='text'> ↓</a>";
												if (!$X["fun"]) {
													echo '<a href="#fieldset-search" title="' . lang(45) . '" class="text jsonly"> =</a>', script("qsl('a').onclick = partial(selectSearch, '" . js_escape($z) . "');");
												}
												echo "</span>";
											}
											$Gc[$z] = $X["fun"];
											next($N);
										}
									}
									$Cd = array();
									if ($_GET["modify"]) {
										foreach ($M
											as $L) {
											foreach ($L
												as $z => $X) $Cd[$z] = max($Cd[$z], min(40, strlen(utf8_decode($X))));
										}
									}
									echo ($Ba ? "<th>" . lang(237) : "") . "</thead>\n";
									if (is_ajax()) {
										if ($_ % 2 == 1 && $F % 2 == 1) odd();
										ob_end_clean();
									}
									foreach ($c->rowDescriptions($M, $Bc) as $Zd => $L) {
										$dh = unique_array($M[$Zd], $x);
										if (!$dh) {
											$dh = array();
											foreach ($M[$Zd] as $z => $X) {
												if (!preg_match('~^(COUNT\((\*|(DISTINCT )?`(?:[^`]|``)+`)\)|(AVG|GROUP_CONCAT|MAX|MIN|SUM)\(`(?:[^`]|``)+`\))$~', $z)) $dh[$z] = $X;
											}
										}
										$eh = "";
										foreach ($dh
											as $z => $X) {
											if (($y == "sql" || $y == "pgsql") && preg_match('~char|text|enum|set~', $o[$z]["type"]) && strlen($X) > 64) {
												$z = (strpos($z, '(') ? $z : idf_escape($z));
												$z = "MD5(" . ($y != 'sql' || preg_match("~^utf8~", $o[$z]["collation"]) ? $z : "CONVERT($z USING " . charset($g) . ")") . ")";
												$X = md5($X);
											}
											$eh .= "&" . ($X !== null ? urlencode("where[" . bracket_escape($z) . "]") . "=" . urlencode($X) : "null%5B%5D=" . urlencode($z));
										}
										echo "<tr" . odd() . ">" . (!$s && $N ? "" : "<td>" . checkbox("check[]", substr($eh, 1), in_array(substr($eh, 1), (array)$_POST["check"])) . ($kd || information_schema(DB) ? "" : " <a href='" . h(ME . "edit=" . urlencode($b) . $eh) . "' class='edit'>" . lang(238) . "</a>"));
										foreach ($L
											as $z => $X) {
											if (isset($ae[$z])) {
												$n = $o[$z];
												$X = $l->value($X, $n);
												if ($X != "" && (!isset($Xb[$z]) || $Xb[$z] != "")) $Xb[$z] = (is_mail($X) ? $ae[$z] : "");
												$A = "";
												if (preg_match('~blob|bytea|raw|file~', $n["type"]) && $X != "") $A = ME . 'download=' . urlencode($b) . '&field=' . urlencode($z) . $eh;
												if (!$A && $X !== null) {
													foreach ((array)$Bc[$z] as $p) {
														if (count($Bc[$z]) == 1 || end($p["source"]) == $z) {
															$A = "";
															foreach ($p["source"] as $t => $bg) $A .= where_link($t, $p["target"][$t], $M[$Zd][$bg]);
															$A = ($p["db"] != "" ? preg_replace('~([?&]db=)[^&]+~', '\1' . urlencode($p["db"]), ME) : ME) . 'select=' . urlencode($p["table"]) . $A;
															if ($p["ns"]) $A = preg_replace('~([?&]ns=)[^&]+~', '\1' . urlencode($p["ns"]), $A);
															if (count($p["source"]) == 1) break;
														}
													}
												}
												if ($z == "COUNT(*)") {
													$A = ME . "select=" . urlencode($b);
													$t = 0;
													foreach ((array)$_GET["where"] as $W) {
														if (!array_key_exists($W["col"], $dh)) $A .= where_link($t++, $W["col"], $W["val"], $W["op"]);
													}
													foreach ($dh
														as $od => $W) $A .= where_link($t++, $od, $W);
												}
												$X = select_value($X, $A, $n, $Fg);
												$u = h("val[$eh][" . bracket_escape($z) . "]");
												$Y = $_POST["val"][$eh][bracket_escape($z)];
												$Sb = !is_array($L[$z]) && is_utf8($X) && $M[$Zd][$z] == $L[$z] && !$Gc[$z];
												$Eg = preg_match('~text|lob~', $n["type"]);
												echo "<td id='$u'";
												if (($_GET["modify"] && $Sb) || $Y !== null) {
													$Nc = h($Y !== null ? $Y : $L[$z]);
													echo ">" . ($Eg ? "<textarea name='$u' cols='30' rows='" . (substr_count($L[$z], "\n") + 1) . "'>$Nc</textarea>" : "<input name='$u' value='$Nc' size='$Cd[$z]'>");
												} else {
													$Gd = strpos($X, "<i>…</i>");
													echo " data-text='" . ($Gd ? 2 : ($Eg ? 1 : 0)) . "'" . ($Sb ? "" : " data-warning='" . h(lang(239)) . "'") . ">$X</td>";
												}
											}
										}
										if ($Ba) echo "<td>";
										$c->backwardKeysPrint($Ba, $M[$Zd]);
										echo "</tr>\n";
									}
									if (is_ajax()) exit;
									echo "</table>\n", "</div>\n";
								}
								if (!is_ajax()) {
									if ($M || $F) {
										$ic = true;
										if ($_GET["page"] != "last") {
											if ($_ == "" || (count($M) < $_ && ($M || !$F))) $Ec = ($F ? $F * $_ : 0) + count($M);
											elseif ($y != "sql" || !$kd) {
												$Ec = ($kd ? false : found_rows($R, $Z));
												if ($Ec < max(1e4, 2 * ($F + 1) * $_)) $Ec = reset(slow_query(count_rows($b, $Z, $kd, $s)));
												else $ic = false;
											}
										}
										$Ke = ($_ != "" && ($Ec === false || $Ec > $_ || $F));
										if ($Ke) {
											echo (($Ec === false ? count($M) + 1 : $Ec - $F * $_) > $_ ? '<p><a href="' . h(remove_from_uri("page") . "&page=" . ($F + 1)) . '" class="loadmore">' . lang(240) . '</a>' . script("qsl('a').onclick = partial(selectLoadMore, " . (+$_) . ", '" . lang(241) . "…');", "") : ''), "\n";
										}
									}
									echo "<div class='footer'><div>\n";
									if ($M || $F) {
										if ($Ke) {
											$Md = ($Ec === false ? $F + (count($M) >= $_ ? 2 : 1) : floor(($Ec - 1) / $_));
											echo "<fieldset>";
											if ($y != "simpledb") {
												echo "<legend><a href='" . h(remove_from_uri("page")) . "'>" . lang(242) . "</a></legend>", script("qsl('a').onclick = function () { pageClick(this.href, +prompt('" . lang(242) . "', '" . ($F + 1) . "')); return false; };"), pagination(0, $F) . ($F > 5 ? " …" : "");
												for ($t = max(1, $F - 4); $t < min($Md, $F + 5); $t++) echo
												pagination($t, $F);
												if ($Md > 0) {
													echo ($F + 5 < $Md ? " …" : ""), ($ic && $Ec !== false ? pagination($Md, $F) : " <a href='" . h(remove_from_uri("page") . "&page=last") . "' title='~$Md'>" . lang(243) . "</a>");
												}
											} else {
												echo "<legend>" . lang(242) . "</legend>", pagination(0, $F) . ($F > 1 ? " …" : ""), ($F ? pagination($F, $F) : ""), ($Md > $F ? pagination($F + 1, $F) . ($Md > $F + 1 ? " …" : "") : "");
											}
											echo "</fieldset>\n";
										}
										echo "<fieldset>", "<legend>" . lang(244) . "</legend>";
										$Ib = ($ic ? "" : "~ ") . $Ec;
										echo
										checkbox("all", 1, 0, ($Ec !== false ? ($ic ? "" : "~ ") . lang(143, $Ec) : ""), "var checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$Ib' : checked); selectCount('selected2', this.checked || !checked ? '$Ib' : checked);") . "\n", "</fieldset>\n";
										if ($c->selectCommandPrint()) {
											echo '<fieldset', ($_GET["modify"] ? '' : ' class="jsonly"'), '><legend>', lang(236), '</legend><div>
<input type="submit" value="', lang(14), '"', ($_GET["modify"] ? '' : ' title="' . lang(232) . '"'), '>
</div></fieldset>
<fieldset><legend>', lang(120), ' <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="', lang(10), '">
<input type="submit" name="clone" value="', lang(228), '">
<input type="submit" name="delete" value="', lang(18), '">', confirm(), '</div></fieldset>
';
										}
										$Cc = $c->dumpFormat();
										foreach ((array)$_GET["columns"] as $d) {
											if ($d["fun"]) {
												unset($Cc['sql']);
												break;
											}
										}
										if ($Cc) {
											print_fieldset("export", lang(62) . " <span id='selected2'></span>");
											$Ie = $c->dumpOutput();
											echo ($Ie ? html_select("output", $Ie, $ma["output"]) . " " : ""), html_select("format", $Cc, $ma["format"]), " <input type='submit' name='export' value='" . lang(62) . "'>\n", "</div></fieldset>\n";
										}
										$c->selectEmailPrint(array_filter($Xb, 'strlen'), $e);
									}
									echo "</div></div>\n";
									if ($c->selectImportPrint()) {
										echo "<div>", "<a href='#import'>" . lang(61) . "</a>", script("qsl('a').onclick = partial(toggle, 'import');", ""), "<span id='import' class='hidden'>: ", "<input type='file' name='csv_file'> ", html_select("separator", array("csv" => "CSV,", "csv;" => "CSV;", "tsv" => "TSV"), $ma["format"], 1);
										echo " <input type='submit' name='import' value='" . lang(61) . "'>", "</span>", "</div>";
									}
									echo "<input type='hidden' name='token' value='$T'>\n", "</form>\n", (!$s && $N ? "" : script("tableCheck();"));
								}
							}
						}
						if (is_ajax()) {
							ob_end_clean();
							exit;
						}
					} elseif (isset($_GET["variables"])) {
						$ig = isset($_GET["status"]);
						page_header($ig ? lang(112) : lang(111));
						$sh = ($ig ? show_status() : show_variables());
						if (!$sh) echo "<p class='message'>" . lang(12) . "\n";
						else {
							echo "<table cellspacing='0'>\n";
							foreach ($sh
								as $z => $X) {
								echo "<tr>", "<th><code class='jush-" . $y . ($ig ? "status" : "set") . "'>" . h($z) . "</code>", "<td>" . h($X);
							}
							echo "</table>\n";
						}
					} elseif (isset($_GET["script"])) {
						header("Content-Type: text/javascript; charset=utf-8");
						if ($_GET["script"] == "db") {
							$rg = array("Data_length" => 0, "Index_length" => 0, "Data_free" => 0);
							foreach (table_status() as $E => $R) {
								json_row("Comment-$E", h($R["Comment"]));
								if (!is_view($R)) {
									foreach (array("Engine", "Collation") as $z) json_row("$z-$E", h($R[$z]));
									foreach ($rg + array("Auto_increment" => 0, "Rows" => 0) as $z => $X) {
										if ($R[$z] != "") {
											$X = format_number($R[$z]);
											json_row("$z-$E", ($z == "Rows" && $X && $R["Engine"] == ($dg == "pgsql" ? "table" : "InnoDB") ? "~ $X" : $X));
											if (isset($rg[$z])) $rg[$z] += ($R["Engine"] != "InnoDB" || $z != "Data_free" ? $R[$z] : 0);
										} elseif (array_key_exists($z, $R)) json_row("$z-$E");
									}
								}
							}
							foreach ($rg
								as $z => $X) json_row("sum-$z", format_number($X));
							json_row("");
						} elseif ($_GET["script"] == "kill") $g->query("KILL " . number($_POST["kill"]));
						else {
							foreach (count_tables($c->databases()) as $k => $X) {
								json_row("tables-$k", $X);
								json_row("size-$k", db_size($k));
							}
							json_row("");
						}
						exit;
					} else {
						$zg = array_merge((array)$_POST["tables"], (array)$_POST["views"]);
						if ($zg && !$m && !$_POST["search"]) {
							$J = true;
							$D = "";
							if ($y == "sql" && $_POST["tables"] && count($_POST["tables"]) > 1 && ($_POST["drop"] || $_POST["truncate"] || $_POST["copy"])) queries("SET foreign_key_checks = 0");
							if ($_POST["truncate"]) {
								if ($_POST["tables"]) $J = truncate_tables($_POST["tables"]);
								$D = lang(245);
							} elseif ($_POST["move"]) {
								$J = move_tables((array)$_POST["tables"], (array)$_POST["views"], $_POST["target"]);
								$D = lang(246);
							} elseif ($_POST["copy"]) {
								$J = copy_tables((array)$_POST["tables"], (array)$_POST["views"], $_POST["target"]);
								$D = lang(247);
							} elseif ($_POST["drop"]) {
								if ($_POST["views"]) $J = drop_views($_POST["views"]);
								if ($J && $_POST["tables"]) $J = drop_tables($_POST["tables"]);
								$D = lang(248);
							} elseif ($y != "sql") {
								$J = ($y == "sqlite" ? queries("VACUUM") : apply_queries("VACUUM" . ($_POST["optimize"] ? "" : " ANALYZE"), $_POST["tables"]));
								$D = lang(249);
							} elseif (!$_POST["tables"]) $D = lang(9);
							elseif ($J = queries(($_POST["optimize"] ? "OPTIMIZE" : ($_POST["check"] ? "CHECK" : ($_POST["repair"] ? "REPAIR" : "ANALYZE"))) . " TABLE " . implode(", ", array_map('idf_escape', $_POST["tables"])))) {
								while ($L = $J->fetch_assoc()) $D .= "<b>" . h($L["Table"]) . "</b>: " . h($L["Msg_text"]) . "<br>";
							}
							queries_redirect(substr(ME, 0, -1), $D, $J);
						}
						page_header(($_GET["ns"] == "" ? lang(26) . ": " . h(DB) : lang(189) . ": " . h($_GET["ns"])), $m, true);
						if ($c->homepage()) {
							if ($_GET["ns"] !== "") {
								echo "<h3 id='tables-views'>" . lang(250) . "</h3>\n";
								$yg = tables_list();
								if (!$yg) echo "<p class='message'>" . lang(9) . "\n";
								else {
									echo "<form action='' method='post'>\n";
									if (support("table")) {
										echo "<fieldset><legend>" . lang(251) . " <span id='selected2'></span></legend><div>", "<input type='search' name='query' value='" . h($_POST["query"]) . "'>", script("qsl('input').onkeydown = partialArg(bodyKeydown, 'search');", ""), " <input type='submit' name='search' value='" . lang(45) . "'>\n", "</div></fieldset>\n";
										if ($_POST["search"] && $_POST["query"] != "") {
											$_GET["where"][0]["op"] = "LIKE %%";
											search_tables();
										}
									}
									echo "<div class='scrollable'>\n", "<table cellspacing='0' class='nowrap checkable'>\n", script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"), '<thead><tr class="wrap">', '<td><input id="check-all" type="checkbox" class="jsonly">' . script("qs('#check-all').onclick = partial(formCheck, /^(tables|views)\[/);", ""), '<th>' . lang(124), '<td>' . lang(252) . doc_link(array('sql' => 'storage-engines.html')), '<td>' . lang(116) . doc_link(array('sql' => 'charset-charsets.html', 'mariadb' => 'supported-character-sets-and-collations/')), '<td>' . lang(253) . doc_link(array('sql' => 'show-table-status.html',)), '<td>' . lang(254) . doc_link(array('sql' => 'show-table-status.html',)), '<td>' . lang(255) . doc_link(array('sql' => 'show-table-status.html')), '<td>' . lang(40) . doc_link(array('sql' => 'example-auto-increment.html', 'mariadb' => 'auto_increment/')), '<td>' . lang(256) . doc_link(array('sql' => 'show-table-status.html',)), (support("comment") ? '<td>' . lang(39) . doc_link(array('sql' => 'show-table-status.html',)) : ''), "</thead>\n";
									$S = 0;
									foreach ($yg
										as $E => $U) {
										$vh = ($U !== null && !preg_match('~table|sequence~i', $U));
										$u = h("Table-" . $E);
										echo '<tr' . odd() . '><td>' . checkbox(($vh ? "views[]" : "tables[]"), $E, in_array($E, $zg, true), "", "", "", $u), '<th>' . (support("table") || support("indexes") ? "<a href='" . h(ME) . "table=" . urlencode($E) . "' title='" . lang(31) . "' id='$u'>" . h($E) . '</a>' : h($E));
										if ($vh) {
											echo '<td colspan="6"><a href="' . h(ME) . "view=" . urlencode($E) . '" title="' . lang(32) . '">' . (preg_match('~materialized~i', $U) ? lang(122) : lang(123)) . '</a>', '<td align="right"><a href="' . h(ME) . "select=" . urlencode($E) . '" title="' . lang(30) . '">?</a>';
										} else {
											foreach (array("Engine" => array(), "Collation" => array(), "Data_length" => array("create", lang(33)), "Index_length" => array("indexes", lang(126)), "Data_free" => array("edit", lang(34)), "Auto_increment" => array("auto_increment=1&create", lang(33)), "Rows" => array("select", lang(30)),) as $z => $A) {
												$u = " id='$z-" . h($E) . "'";
												echo ($A ? "<td align='right'>" . (support("table") || $z == "Rows" || (support("indexes") && $z != "Data_length") ? "<a href='" . h(ME . "$A[0]=") . urlencode($E) . "'$u title='$A[1]'>?</a>" : "<span$u>?</span>") : "<td id='$z-" . h($E) . "'>");
											}
											$S++;
										}
										echo (support("comment") ? "<td id='Comment-" . h($E) . "'>" : "");
									}
									echo "<tr><td><th>" . lang(229, count($yg)), "<td>" . h($y == "sql" ? $g->result("SELECT @@default_storage_engine") : ""), "<td>" . h(db_collation(DB, collations()));
									foreach (array("Data_length", "Index_length", "Data_free") as $z) echo "<td align='right' id='sum-$z'>";
									echo "</table>\n", "</div>\n";
									if (!information_schema(DB)) {
										echo "<div class='footer'><div>\n";
										$qh = "<input type='submit' value='" . lang(257) . "'> " . on_help("'VACUUM'");
										$we = "<input type='submit' name='optimize' value='" . lang(258) . "'> " . on_help($y == "sql" ? "'OPTIMIZE TABLE'" : "'VACUUM OPTIMIZE'");
										echo "<fieldset><legend>" . lang(120) . " <span id='selected'></span></legend><div>" . ($y == "sqlite" ? $qh : ($y == "pgsql" ? $qh . $we : ($y == "sql" ? "<input type='submit' value='" . lang(259) . "'> " . on_help("'ANALYZE TABLE'") . $we . "<input type='submit' name='check' value='" . lang(260) . "'> " . on_help("'CHECK TABLE'") . "<input type='submit' name='repair' value='" . lang(261) . "'> " . on_help("'REPAIR TABLE'") : ""))) . "<input type='submit' name='truncate' value='" . lang(262) . "'> " . on_help($y == "sqlite" ? "'DELETE'" : "'TRUNCATE" . ($y == "pgsql" ? "'" : " TABLE'")) . confirm() . "<input type='submit' name='drop' value='" . lang(121) . "'>" . on_help("'DROP TABLE'") . confirm() . "\n";
										$j = (support("scheme") ? $c->schemas() : $c->databases());
										if (count($j) != 1 && $y != "sqlite") {
											$k = (isset($_POST["target"]) ? $_POST["target"] : (support("scheme") ? $_GET["ns"] : DB));
											echo "<p>" . lang(263) . ": ", ($j ? html_select("target", $j, $k) : '<input name="target" value="' . h($k) . '" autocapitalize="off">'), " <input type='submit' name='move' value='" . lang(264) . "'>", (support("copy") ? " <input type='submit' name='copy' value='" . lang(265) . "'> " . checkbox("overwrite", 1, $_POST["overwrite"], lang(266)) : ""), "\n";
										}
										echo "<input type='hidden' name='all' value=''>";
										echo
										script("qsl('input').onclick = function () { selectCount('selected', formChecked(this, /^(tables|views)\[/));" . (support("table") ? " selectCount('selected2', formChecked(this, /^tables\[/) || $S);" : "") . " }"), "<input type='hidden' name='token' value='$T'>\n", "</div></fieldset>\n", "</div></div>\n";
									}
									echo "</form>\n", script("tableCheck();");
								}
								echo '<p class="links"><a href="' . h(ME) . 'create=">' . lang(63) . "</a>\n", (support("view") ? '<a href="' . h(ME) . 'view=">' . lang(195) . "</a>\n" : "");
								if (support("routine")) {
									echo "<h3 id='routines'>" . lang(136) . "</h3>\n";
									$Hf = routines();
									if ($Hf) {
										echo "<table cellspacing='0'>\n", '<thead><tr><th>' . lang(176) . '<td>' . lang(38) . '<td>' . lang(212) . "<td></thead>\n";
										odd('');
										foreach ($Hf
											as $L) {
											$E = ($L["SPECIFIC_NAME"] == $L["ROUTINE_NAME"] ? "" : "&name=" . urlencode($L["ROUTINE_NAME"]));
											echo '<tr' . odd() . '>', '<th><a href="' . h(ME . ($L["ROUTINE_TYPE"] != "PROCEDURE" ? 'callf=' : 'call=') . urlencode($L["SPECIFIC_NAME"]) . $E) . '">' . h($L["ROUTINE_NAME"]) . '</a>', '<td>' . h($L["ROUTINE_TYPE"]), '<td>' . h($L["DTD_IDENTIFIER"]), '<td><a href="' . h(ME . ($L["ROUTINE_TYPE"] != "PROCEDURE" ? 'function=' : 'procedure=') . urlencode($L["SPECIFIC_NAME"]) . $E) . '">' . lang(129) . "</a>";
										}
										echo "</table>\n";
									}
									echo '<p class="links">' . (support("procedure") ? '<a href="' . h(ME) . 'procedure=">' . lang(211) . '</a>' : '') . '<a href="' . h(ME) . 'function=">' . lang(210) . "</a>\n";
								}
								if (support("event")) {
									echo "<h3 id='events'>" . lang(137) . "</h3>\n";
									$M = get_rows("SHOW EVENTS");
									if ($M) {
										echo "<table cellspacing='0'>\n", "<thead><tr><th>" . lang(176) . "<td>" . lang(267) . "<td>" . lang(201) . "<td>" . lang(202) . "<td></thead>\n";
										foreach ($M
											as $L) {
											echo "<tr>", "<th>" . h($L["Name"]), "<td>" . ($L["Execute at"] ? lang(268) . "<td>" . $L["Execute at"] : lang(203) . " " . $L["Interval value"] . " " . $L["Interval field"] . "<td>$L[Starts]"), "<td>$L[Ends]", '<td><a href="' . h(ME) . 'event=' . urlencode($L["Name"]) . '">' . lang(129) . '</a>';
										}
										echo "</table>\n";
										$gc = $g->result("SELECT @@event_scheduler");
										if ($gc && $gc != "ON") echo "<p class='error'><code class='jush-sqlset'>event_scheduler</code>: " . h($gc) . "\n";
									}
									echo '<p class="links"><a href="' . h(ME) . 'event=">' . lang(200) . "</a>\n";
								}
								if ($yg) echo
								script("ajaxSetHtml('" . js_escape(ME) . "script=db');");
							}
						}
					}
					page_footer();
