<?php
include_once ("/home2/ebizdev/monopalace.com/config/config.php");
include_once (INC_DIR."/class/ebiz_basic.class.php");


$ver = $_GET["ver"];
$mobile_chk = (!$ver || $ver == "mobile") ? "on" : "off";

if(!empty($_GET["NVKWD"]) || !empty($_GET["DMKW"])) {

	if(!empty($_GET["NVKWD"])) { $keyword	= urldecode($_GET["NVKWD"]); } 
	else if(!empty($_GET["DMKW"])) { $keyword	= urldecode($_GET["DMKW"]); }  
	
	$refer		= $_SERVER["HTTP_REFERER"];
	$userip		= $_SERVER["REMOTE_ADDR"];
	$wdate		= date("Y-m-d H:i:s");
	
	$phr = parse_url($refer);
	switch($phr["host"]) {
		case "search.naver.com":
		case "m.search.naver.com":
			$mflag = "naver";
			break;

		case "search.daum.net":
		case "m.search.daum.net":
			$mflag = "daum";
			break;

		default : 
			$mflag = $phr["host"];
			break;

	}

	$ebiz_db->query("insert into http_refer set m_flag='".$mflag."', keyword='".$keyword."', site='monopalace', userip='".$userip."', wdate='".$wdate."'");
}

$pos		= (!empty($cur_pos)) ? $cur_pos : 1;
$is_over	= (!empty($_GET["dd"])) ? $ebiz_control->XORDecode($_GET["dd"]) : $ebiz_control->mm($pos);
$tt			= $ebiz_control->is_title($is_over);
$ebiz_ip	= $ebiz_control->ebiz_ip($_SERVER["REMOTE_ADDR"]);

if($_SERVER["REMOTE_ADDR"] == "61.74.233.194") {
// 현재 페이지 노출
//	echo $is_over;
}

if(!empty($_GET["brd"])) {
	if(!empty($_GET["num"])) {
		$board_info	= $ebiz_board->ebiz_board_view($_GET["brd"], $_GET["num"]);
		$tt.= $board_info["subject"]." &gt; ";
		$board_writer = ($board_info["writer"] == SITE_NAME_K) ? "<img src=\"".base_img."/icon_monopalace.gif\" alt=\"".SITE_NAME_K."\"/>" : $board_info["writer"];
		$brd_mode = "qna_modify";
		$is_file = ($board_info["file1"]) ? "<a href=\"http://file.monopalace.com/board/".$board_info["file1"]."\" target=\"_blank\">".$board_info["file1"]."</a>" : "첨부된 파일이 없습니다.";
	} else {
		$brd_mode = "qna_write";
	}

	$brd_infos = $ebiz_board->ebiz_board_config($_GET["brd"]);
	$tt.= $brd_infos["brd_name"];
}

//유입수 체크 시작
//이전페이지 체크 임시로 막음 2014-04-08
$base_date = date("Y-m-d");
$base_time = "h".date("H");

	$_count = $ebiz_db->queryrows("select num from home_enter where userip='".base_ip."' and wdate='".$base_date."' and flag='mono'");
	if($_count < 1) {
		//insert
		$ebiz_db->query("insert into home_enter set userip='".base_ip."', flag='mono', count=1, wdate='".$base_date."', ".$base_time." = ".$base_time." + 1");
	} else if($_count > 0) {
		//update
		$ebiz_db->query("update home_enter set count=count+1, ".$base_time."=".$base_time." + 1 where userip='".base_ip."' and wdate='".$base_date."' and flag='mono'");
	}

//유입수 체크 끝
include_once (INC_DIR.'/config/log_query.php');
//로봇테스트

$rip = $_SERVER["REMOTE_ADDR"];
$agent = $_SERVER["HTTP_USER_AGENT"];
$_agent = explode(" ", $agent);
$xx = 0;
while($_agent[$xx]) {
	if($_agent[$xx] == "MSIE") {
		$robot = 'N';
		break;
	}
	if($_agent[$xx] == "Windows") {
		$robot = 'N';
		break;
	}
	if($_agent[$xx] == "Intel") {
		$robot = 'N';
		break;
	}
	if($_agent[$xx] == "PPC") {
		$robot = 'N';
		break;
	}
	if($_agent[$xx] == "Mac") {
		$robot = 'N';
		break;
	}
	if($_agent[$xx] == "iPhone") {
		$robot = 'N';
		break;
	}
	if($_agent[$xx] == "rv:11.0)") {
		$robot = 'N';
		break;
	}
	$sear = strpos($_agent[$xx], "Chrome");
	if($sear !== false) {
		$robot = "N";
		break;
	}
	$xx++;
}
if($robot != "N") {
	$ebiz_db->query("insert into robot_log set flag='mono', ip='".$rip."', agent='".$agent."', wdate=now()");
}
// -------- is_over 변환 시작

switch($is_over){
	case "standard_46a":
	case "standard_46at":
	case "tworoom":
		$is_over = "standard_A";
	break;
	case "suite_46s":
	case "suite_46st":
		$is_over = "suite";
	break;
	case "deluxe_58d":
	case "deluxe_58dt":
		$is_over = "deluxe";
	break;
	case "modelhouse_info":
		$is_over = "intro";
	break;
	case "thema_03":
	case "thema_04":
	case "thema_05":
	case "thema_06":
	case "thema_07":
	case "thema_08":
	case "thema_09":
	case "thema_10":
	case "thema_11":
	case "thema_12":
	case "thema_13":
	case "thema_14":
		$is_over = "thema";
	break;
	
}

	switch($is_over) {
		case "FaQ" : 
			$is_over = "FaQ1";
		break;
	}

$arrMobileAgent = array('iPhone','Mobile','UP.Browser','Android','BlackBerry','Windows CE','Nokia','webOS','Opera Mini','SonyEricsson','opera mobi','Windows Phone','IEMobile','POLARIS','lgtelecom','NATEBrowser','AppleWebKit', "iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");
	$arrExAgent = array('Macintosh','OpenBSD','SunOS','X11','QNX','BeOS', 'OS\/2','Windows NT', "iphone","lgtelecom","skt","mobile","samsung","nokia","blackberry","android","android","sony","phone");
	$isMobile = "";
	if($_GET["ver"]=="pc") {
		setcookie("pc", "Y", 0,"/");
	}
	if($_COOKIE['pc'] == "Y") {
		$mobile_chk = "off";
	}
	if(preg_match('/('.implode('|',$arrMobileAgent).')/i', $_SERVER['HTTP_USER_AGENT']) ){
		$isMobile = "Y";
		if(preg_match('/(AppleWebKit)/i',$_SERVER['HTTP_USER_AGENT']) && preg_match('/(Macintosh;)/i',$_SERVER['HTTP_USER_AGENT'])) $isMobile = "N";
		if(preg_match('/(AppleWebKit)/i',$_SERVER['HTTP_USER_AGENT']) && preg_match('/(Windows;)/i',$_SERVER['HTTP_USER_AGENT'])) $isMobile = "N";
		if(preg_match('/(Windows CE)/i',$_SERVER['HTTP_USER_AGENT']) && !preg_match('/(compatible;)/i',$_SERVER['HTTP_USER_AGENT']) && !preg_match('/(IEMobile)/i',$_SERVER['HTTP_USER_AGENT'])) $isMobile = "N";
		if(preg_match('/(AppleWebKit)/i',$_SERVER['HTTP_USER_AGENT']) && preg_match('/(Linux;)/i',$_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile')) $isMobile = "N";
		if(preg_match('/(Windows NT)/i',$_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile')) $isMobile = "N";
	}

	$chk_view_pc = (!empty($_COOKIE['pc']) && $_COOKIE['pc'] == "Y") || (!empty($_GET['pc']) && $_GET['pc'] == "Y") ? "Y" : "";
	$tmpReferer = !empty($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER']) : array("host"=>"");
	
	$tmpReferer['host'] = str_replace(array("dev.", "m.", "www.", "dev.m."), "", $tmpReferer['host']);
	if($isMobile == "Y" && ($chk_view_pc != "Y" || $tmpReferer['host'] != "monopalace.com")){
		//$_SERVER['REQUEST_URI'] = urlencode($_SERVER['REQUEST_URI']);
		$goURL	= "http://m.monopalace.com".$_SERVER["REQUEST_URI"];
		header("location:".$goURL);
		exit;
	}


?>

<!DOCTYPE html>
<html lang="ko-KR">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>모노팰리스</title>

    <meta name="title" content="e-dien">
    <meta name="author" content="e-dien">
    <meta name="subject" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="classification" content="e-dien">

    <!-- 오픈그래프 -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="e-dien">
    <meta property="og:description" content="">
    <meta property="og:url" content="">
    <meta property="og:site_name" content="e-dien">
    <meta property="og:image" content="">

    <link rel="canonical" href="">

    <link rel="shortcut icon" href="http://img.monopalace.com/monopalace.ico">
    <!-- Android icon -->
    <link rel="shortcut icon" href="http://img.monopalace.com/monopalace_icon128x128.png">
    <!-- iPhone icon -->
    <link rel="apple-touch-icon" sizes="57x57" href="http://img.monopalace.com/monopalace_icon57x57.png">
    <!-- iPad icon -->
    <link rel="apple-touch-icon" sizes="72x72" href="http://img.monopalace.com/monopalace_icon72x72.png">
    <!-- iPhone icon(Retina) -->
    <link rel="apple-touch-icon" sizes="114x114" href="http://img.monopalace.com/monopalace_icon114x114.png">
    <!-- iPad icon(Retina) -->
    <link rel="apple-touch-icon" sizes="144x144" href="http://img.monopalace.com/monopalace_icon144x144.png">

    <!-- http://static.monopalace.com/ -->
    <!-- fonts -->
    <!-- <link rel="stylesheet" href="http://static.monopalace.com/fonts/notosanskr/notosanskr.css">
    <link rel="stylesheet" href="http://static.monopalace.com/fonts/nanumgothic/nanumgothic.css"> -->
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- css -->
    <link rel="stylesheet" href="http://static.monopalace.com/css/reset.css">
    <link rel="stylesheet" href="http://static.monopalace.com/fonts/mdi/css/materialdesignicons.min.css">
    <!-- <link rel="stylesheet" href="http://static.monopalace.com/css/libs/slick/slick.css">
    <link rel="stylesheet" href="http://static.monopalace.com/css/libs/slick/slick-theme.css"> -->
    <!-- <link rel="stylesheet" href="http://static.monopalace.com/css/common.min.css"> -->
    <!-- <link rel="stylesheet" href="http://static.monopalace.com/css/contents.min.css"> -->
    <link rel="stylesheet" href="http://static.monopalace.com/css/libs/prettyPhoto.css">
    <link rel="stylesheet" href="http://static.monopalace.com/css/landing/contents.min.css">

    <script type="text/javascript" src="http://static.monopalace.com/js/libs/jquery-1.12.1.min.js"></script>

</head>

<body id="landingInquiryPage">
    <div id="wrapper" class="wrapper">

        <header class="landing-header">
            <div id="gnb" class="gnb">
                <div class="group-f gnb-area">
                    <h1 id="logo-cover" class="logo-cover">
                        <a href="javascript:;" class="logo">
                            <img src="http://img.monopalace.com/landing/inquiry/logo.png" alt="모노팰리스" class="logo-img">
                        </a>
                    </h1>

                    <div id="navbar" class="navbar">
                        <nav id="mainNav" class="main-nav">
                            <h1 class="sr-only">메인메뉴</h1>
                            <ul class="menu cf">
                                <li>
                                    <a href="#scroll_premier">프리미엄 입지환경</a>
                                </li>
                                <li>
                                    <a href="#scroll_sale">임대수익</a>
                                </li>
                                <li>
                                    <a href="#scroll_intro">상품소개</a>
                                </li>
                                <li>
                                    <a href="#scroll_type">타입 및 특화시설</a>
                                </li>
                                <li>
                                    <a href="#scroll_plan">지역개발호재</a>
                                </li>
                                <li>
                                    <a href="#scroll_location">오시는 길</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <button type="button" class="ap-reset inquiry-tab focus-no" data-toggle="modal" data-target="inquiry-modal" aria-label="문의하기 토글버튼">
                    온라인 문의하기
                </button>
            </div>
        </header>

        <main id="container" class="container" role="main">
            <section class="sec landing inquiry-visual-sec">
                <div class="visual-wrapper">
                    <div class="visual-content">
                        <div class="banner">
                            <h1 class="title">
                                <img src="http://img.monopalace.com/landing/inquiry/visual/title.png" alt="충남아산의 명품 랜드마크 모노팰리스">
                            </h1>
                            <img src="http://img.monopalace.com/landing/inquiry/visual/subtitle.png" alt="월세수익 확정 보장받는 수익형상품!">
                            <img src="http://img.monopalace.com/landing/inquiry/visual/advantages.png" alt="156세대, 최적의 교통환경, 프리미엄 입지환경, 저렴한 분양가, 월세 수익 연 500만원">
                        </div>
                        <div class="bannerbar">
                            <div class="title-area">
                                <div class="lg-wd">
                                    <img src="http://img.monopalace.com/landing/inquiry/visual/bar_title.png" alt="5년간 임대수익보장!" class="bar-title">
                                    <a href="#scroll_sale" class="bar-btn"><img src="http://img.monopalace.com/landing/inquiry/visual/bar_btn.png" alt="분양혜택확인"></a>
                                </div>
                            </div>
                            <div class="number-area">
                                <img src="http://img.monopalace.com/landing/inquiry/visual/bar_inquiry_number.png" alt="분양문의 041 585 0003">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-visual-sec -->
            <section class="sec landing inquiry-premier-sec" id="scroll_premier">
                <div class="premier-wrapper">
                    <div class="premier-content group-f">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/premier/title.png" alt="모노팰리스 분양 특급 프리미엄">
                        </h1>
                        <p class="subtitle-type1 subtitle">
                            아산시 랜드마크! 특급 인기 소형아파트 모노팰리스! <br>
                            <span class="c-brown">풍성한 생활인프라와 풍부한 임대수요까지!</span>
                        </p>
                        <div class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/premier/img_1.png" alt="1호선 온양온천역 프리미엄 역세권">
                            <img src="http://img.monopalace.com/landing/inquiry/premier/img_2.png" alt="풍부한 임대수요!">
                            <img src="http://img.monopalace.com/landing/inquiry/premier/img_3.png" alt="쇼핑, 문화, 생활 인프라">
                            <img src="http://img.monopalace.com/landing/inquiry/premier/img_4.png" alt="풍부한 교육환경, 의료시설 밀집">
                            <img src="http://img.monopalace.com/landing/inquiry/premier/img_5.png" alt="살기 좋은 힐링 생활권">
                            <img src="http://img.monopalace.com/landing/inquiry/premier/img_6.png" alt="최첨단 특화시설">
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-premier-sec -->
            <section class="sec landing inquiry-place-sec" id="scroll_place">
                <div class="place-wrapper">
                    <div class="place-content group-f">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/place/title.png" alt="프리이엄 입지환경">
                        </h1>
                        <p class="subtitle-type1 subtitle">
                            아산시 최고의 자리에 교통, 생활, 교육, 자연 등 <br>
                            <span class="c-brown">특급교통과 완벽한 인프라를 갖춘 명품 주거입지!</span>
                        </p>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/place/img_1.png" alt="모노팰리스 입지환경 약도">
                        </figure>
                        <p class="subtext">
                            - 도보 7분거리 <span class="c-red fw-m">1호선 온양온천역으로 청량리, 광운대, 천안 등을 빠르게 이동</span> 가능<br>
                            - <span class="c-red fw-m">이마트, 롯데마트, 롯데시네마, 온양전통시장</span> 등 편의시설이 잘 갖춰진 주거환경<br>
                            - 도보 10분 거리 <span class="c-red fw-m">시외버스터미널, 코레일, 모노팰리스 바로앞 버스정류장</span><br>
                            - 교통 여건이 잘 갖추어져 있는 <span class="c-red fw-m">아산 최고 입지</span><br>
                        </p>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-place-sec -->
            <section class="sec landing inquiry-sale-sec" id="scroll_sale">
                <div class="sale-wrapper">
                    <div class="sale-content group-f">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/sale/title.png" alt="모노팰리스 파격 분양혜택">
                        </h1>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/sale/subtitle.png" alt="수익형 상품!">
                        </figure>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/sale/img_1.png" alt="수익형상품, 후분양상품, 안정성 UP">
                            <a href="http://img.monopalace.com/landing/inquiry/pop_guarantee.jpg" rel="prettyPhoto[guarantee]" title="보증서" class="guarantee-btn">보증서 확인</a>
                        </figure>
                        <p class="subtitle-type1 subtitle">
                            건축 시행사가 준공 후 5년동안 직접 임대운영중인 믿을 수 있는 후분양 상품입니다. <br>
                            모노팰리스 방문하시고, <span class="c-yellow td-under">전실 만실로 돌아 가는 것을 확인하고 계약 하세요!!</span>
                        </p>
                    </div>
                </div>
                <div class="income-wrapper">
                    <div class="income-content group-f">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/sale/income_title.png" alt="든든하다! 모노팰리스 임대수익">
                        </h1>
                        <p class="subtitle-type1 subtitle">
                            시중 예금 금리 인하, <span class="c-skyblue">평균 시중은행 예금 금리 1% 수준</span> <br>
                            <span class="c-red">저금리 시대 최고의 수익률<i>!</i></span>
                        </p>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/sale/income_img_1.png" alt="1억 시중은행 예금상품 예치시 연 이자 1059950원">
                            <img src="http://img.monopalace.com/landing/inquiry/sale/income_img_2.png" alt="모노팰리스 분양시 임대수익 5000000원 5년무조건">
                        </figure>
                        <figure class="picture-2 ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/sale/income_bn_1.png" alt="실수입 보장" class="bn-1">
                        </figure>
                        <p class="subtext">
                            ※ 실제 수익률 및 대출금액은 개인신용에 따라 변동 될 수 있습니다.
                        </p>
                    </div>
                    <div class="banner-wrapper">
                        <div class="banner-content group-f">
                            <p>망설일 이유 없는 확실한 투자처!</p>
                            <p><span class="c-blue">월세수익 연 480만원 X 5년 동안</span> <span class="c-red-dark">= 총 2,400만원 지급 보증</span></p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-income-sec -->
            <section class="sec landing inquiry-intro-sec" id="scroll_intro">
                <div class="intro-wrapper">
                    <div class="intro-content group-f">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/intro/title.png" alt="상품소개">
                        </h1>
                        <p class="subtitle-type1 subtitle">
                            지하 1층~지상 15층 156세대 규모의 인기 소형아파트! <br>
                            <span class="c-brown">주변에서 쉽게 눈에 띄는 가시성과 존재감</span>이 뛰어납니다.
                        </p>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/intro/img_1.png" alt="모노팰리스 소개이미지">
                        </figure>

                        <div class="info-1">
                            <p>충청남도 아산시 온천동 74-5번 (온천대로 1555)</p>
                            <p>
                                <img src="http://img.monopalace.com/landing/inquiry/intro/156.png" alt="모노팰리스 소개이미지" class="img-156">
                                <span>분양면적 : 45.82㎡(원룸 · 투베이), 57.73㎡(원룸 · 투베이)</span>
                            </p>
                        </div>
                        <table class="info-tbl">
                            <colgroup>
                                <col>
                                <col>
                                <col>
                                <col>
                            </colgroup>
                            <tr>
                                <th>사업명</th>
                                <td>모노팰리스</td>
                                <th>대지면적</th>
                                <td>1,185.9㎡ (358.7평)</td>
                            </tr>
                            <tr>
                                <th>준공일</th>
                                <td>2014년 3월 27일</td>
                                <th>연면적</th>
                                <td>8,207.83㎡ (2,482.8평)</td>
                            </tr>
                            <tr>
                                <th>건축규모</th>
                                <td colspan="3">주거시설: 지상 3층 ~ 지상 15층 / 상업시설: 지하 1층 ~ 지상 2층</td>
                            </tr>
                            <tr>
                                <th>세대정보</th>
                                <td colspan="3">주상복합형 소형아파트 총 156세대</td>
                            </tr>
                            <tr>
                                <th>부대시설</th>
                                <td colspan="3">편의점, 근린생활시설, 휘트니스센터, 주차타워, 관리사무소</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-intro-sec -->
            <section class="sec landing inquiry-type-sec" id="scroll_type">
                <div class="type-wrapper">
                    <div class="type-content group-f">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/type/title.png" alt="상품타입">
                        </h1>
                        <p class="subtitle-type1 subtitle">
                            변화하는 라이프 스타일을 저격한 시스템 <br>
                            1인 가구의 니즈를 반영한 편리한 풀옵션, 최첨단 특화시설
                        </p>
                        <ul class="type-list">
                            <li class="type-item">
                                <div class="type-info">
                                    <div class="type-title">
                                        <h2><img src="http://img.monopalace.com/landing/inquiry/type/46a_at_title.png" alt="46a at 타입"></h2>
                                    </div>
                                    <table class="type-tbl">
                                        <colgropu>
                                            <col>
                                            <col>
                                        </colgropu>
                                        <thead>
                                            <tr>
                                                <th colspan="2">실 10평/ 14평형</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>전용면적</th>
                                                <td>３1.78㎡</td>
                                            </tr>
                                            <tr>
                                                <th>공용면적</th>
                                                <td>11.16㎡</td>
                                            </tr>
                                            <tr>
                                                <th>계약면적</th>
                                                <td>45.82(14평)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a href="http://img.monopalace.com/photo/big_46a_01.jpg" rel="prettyPhoto[gallery46a_at]" title="46a" class="gallery-btn">갤러리</a>
                                    <img src="http://img.monopalace.com/landing/inquiry/type/fulloption_txt.png" alt="완벽한 풀옵션" class="option-title">
                                    <p class="option-text">
                                        드럼세탁기, 원터치 전기 쿡탑, 벽걸이 에어컨, <br>
                                        냉장고(255L), 32인치 LED TV, 아일랜드 식탁, <br>
                                        붙박이장, 천정형 빨래건조대, 블라트 컨텐 등
                                    </p>
                                    <!-- 46a -->
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_02.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_03.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_04.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_05.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_06.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_07.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_08.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46a_09.jpg" rel="prettyPhoto[gallery46a_at]" title="46a"></a>
                                    <!-- 46at -->
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_01.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_02.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_03.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_04.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_05.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_06.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_07.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_08.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46at_09.jpg" rel="prettyPhoto[gallery46a_at]" title="46at"></a>
                                </div>
                                <div class="type-picture">
                                    <img src="http://img.monopalace.com/landing/inquiry/type/46a_drawing.png" alt="46a타입">
                                    <img src="http://img.monopalace.com/landing/inquiry/type/46at_drawing.png" alt="46at타입">
                                </div>
                            </li>
                            <li class="type-item">
                                <div class="type-info">
                                    <div class="type-title">
                                        <h2><img src="http://img.monopalace.com/landing/inquiry/type/46s_st_title.png" alt="46s st 타입"></h2>
                                    </div>
                                    <table class="type-tbl">
                                        <colgropu>
                                            <col>
                                            <col>
                                        </colgropu>
                                        <thead>
                                            <tr>
                                                <th colspan="2">실 10평/ 14평형</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>전용면적</th>
                                                <td>３1.78㎡</td>
                                            </tr>
                                            <tr>
                                                <th>공용면적</th>
                                                <td>11.16㎡</td>
                                            </tr>
                                            <tr>
                                                <th>계약면적</th>
                                                <td>45.82(14평)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a href="http://img.monopalace.com/photo/big_46s_01.jpg" rel="prettyPhoto[gallery46s_st]" title="46s" class="gallery-btn">갤러리</a>
                                    <img src="http://img.monopalace.com/landing/inquiry/type/fulloption_txt.png" alt="완벽한 풀옵션" class="option-title">
                                    <p class="option-text">
                                        드럼세탁기, 원터치 전기 쿡탑, 벽걸이 에어컨, <br>
                                        냉장고(255L), 32인치 LED TV, 아일랜드 식탁, <br>
                                        붙박이장, 천정형 빨래건조대, 블라트 컨텐 등
                                    </p>

                                    <!-- 46s -->
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_02.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_03.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_04.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_05.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_06.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_07.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_08.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46s_09.jpg" rel="prettyPhoto[gallery46s_st]" title="46s"></a>
                                    <!-- 46st -->
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_01.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_02.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_03.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_04.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_05.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_06.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_07.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_08.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_46st_09.jpg" rel="prettyPhoto[gallery46s_st]" title="46st"></a>
                                </div>
                                <div class="type-picture">
                                    <img src="http://img.monopalace.com/landing/inquiry/type/46s_drawing.png" alt="46s타입">
                                    <img src="http://img.monopalace.com/landing/inquiry/type/46st_drawing.png" alt="46st타입">
                                </div>
                            </li>
                            <li class="type-item">
                                <div class="type-info">
                                    <div class="type-title">
                                        <h2><img src="http://img.monopalace.com/landing/inquiry/type/58d_dt_title.png" alt="58d dt 타입"></h2>
                                    </div>
                                    <table class="type-tbl">
                                        <colgropu>
                                            <col>
                                            <col>
                                        </colgropu>
                                        <thead>
                                            <tr>
                                                <th colspan="2">실 12평/ 18평형</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>전용면적</th>
                                                <td>39.98㎡</td>
                                            </tr>
                                            <tr>
                                                <th>공용면적</th>
                                                <td>14.08㎡</td>
                                            </tr>
                                            <tr>
                                                <th>계약면적</th>
                                                <td>57.73㎡(18평)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a href="http://img.monopalace.com/photo/big_58d_01.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d" class="gallery-btn">갤러리</a>
                                    <img src="http://img.monopalace.com/landing/inquiry/type/fulloption_txt.png" alt="완벽한 풀옵션" class="option-title">
                                    <p class="option-text">
                                        드럼세탁기, 원터치 전기 쿡탑, 벽걸이 에어컨, <br>
                                        냉장고(255L), 32인치 LED TV, 아일랜드 식탁, <br>
                                        붙박이장, 천정형 빨래건조대, 블라트 컨텐 등
                                    </p>

                                    <!-- 46s -->
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_02.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_03.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_04.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_05.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_06.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_07.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_08.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58d_09.jpg" rel="prettyPhoto[gallery58d_dt]" title="58d"></a>
                                    <!-- 46st -->
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_01.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_02.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_03.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_04.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_05.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_06.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_07.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_08.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                    <a class="hidden" href="http://img.monopalace.com/photo/big_58dt_09.jpg" rel="prettyPhoto[gallery58d_dt]" title="58dt"></a>
                                </div>
                                <div class="type-picture">
                                    <img src="http://img.monopalace.com/landing/inquiry/type/58d_drawing.png" alt="58d타입">
                                    <img src="http://img.monopalace.com/landing/inquiry/type/58dt_drawing.png" alt="58dt타입">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-type-sec -->
            <section class="sec landing inquiry-prefer-sec">
                <div class="prefer-wrapper">
                    <div class="prefer-content">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/prefer/title.png" alt="오피스텔형 주택 선호도 2위">
                        </h1>
                        <p class="subtitle-type1 subtitle">
                            <span class="c-blue">
                                통계청 자료에 따르면 1인가구는 584만 가구로 1인 가구비율이 29.3%의 비중을 차지하며<br>
                                그 추세가 계속 증가하고 있습니다.<br>
                            </span>
                            이를 반영하듯 아파트나 연립주택과 비교해 주거공간이 쾌적하고<br>
                            <span class="c-purple">
                                1~2인 중심 라이프타일이 잘 반영된 오피스텔형 주택 선호도가 높아지고 있으며<br>
                                특히 모노팰리스와 같이 특화된 최신식 시스템을 적용한 주상복합 소형아파트는<br>
                                2~40대 직장인, 대학생 층의 선호도가 매우 높습니다.<br>
                            </span>
                        </p>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-prefer-sec -->
            <section class="sec landing inquiry-interior-sec">
                <div class="interior-wrapper">
                    <div class="interior-content group-f">
                        <h1 class="title-type1 title ta-left">
                            <img src="http://img.monopalace.com/landing/inquiry/interior/title.png" alt="인테리어">
                        </h1>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/interior/img_1.png" alt="전층 복도 테마가 있는 아트갤러리">
                            <img src="http://img.monopalace.com/landing/inquiry/interior/img_2.png" alt="심플하고 고급스러운 인테리어">
                        </figure>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-interior-sec -->
            <section class="sec landing inquiry-system-sec">
                <div class="system-wrapper">
                    <div class="system-content group-f">
                        <h1 class="title-type1 title ta-left">
                            <img src="http://img.monopalace.com/landing/inquiry/system/title.png" alt="특화시스템">
                        </h1>
                        <p class="subtitle-type1 subtitle">

                        </p>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/system/img_1.png" alt="">
                        </figure>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-system-sec -->
            <section class="sec landing inquiry-plan-sec" id="scroll_plan">
                <div class="plan-wrapper">
                    <div class="plan-content group-f">
                        <h1 class="title-type1 title">
                            <img src="http://img.monopalace.com/landing/inquiry/plan/title.png" alt="도시계획 및 개발 호재">
                        </h1>
                        <p class="subtitle-type1 subtitle fw-m">
                            아산시는 지속적인 도시개발로 인구가 꾸준히 상승중이며<br>
                            <span class="c-blue">기존 삼성디스플레이를 비롯한 대규모 산업단지와 신규 개발호재</span>로 많은 인구집중이 예상되어<br>
                            풍부한 임대수요를 기대할 수 있습니다.<br>
                        </p>
                        <figure class="picture ta-center">
                            <img src="http://img.monopalace.com/landing/inquiry/plan/img_1.png" alt="2030년 아산시 인구목표 차트그래프">
                        </figure>
                        <p class="subtitle-type1 subtitle-2">
                            아산시는 12년간 평균 3.7%의 인구증가, 세대수는 3.44%의 증가<br>
                            핵가족화 진행 및 외부 유입 세대 1~2인 세대가 대다수!
                        </p>
                        <div class="picture-list d-flex">
                            <div class="picture">
                                <img src="http://img.monopalace.com/landing/inquiry/plan/img_2.png" alt="2030년 교통망 구성 계획">
                                <p class="text">
                                    아산과 천안에 <span class="c-blue">핵심 인프라가 될 아산 천안간 고속도로가<br>
                                        2020년 준공예정</span>으로 경부고속도로와 연계 당진-아산-천안을<br>
                                    최단거리로 연결하여 충남지역 개발 촉진이 예상됩니다.
                                </p>
                            </div>
                            <div class="picture">
                                <img src="http://img.monopalace.com/landing/inquiry/plan/img_3.png" alt="아산시 주요계획지표">
                                <p class="text">
                                    아산시는 전체 면적 557.21㎢ 을 권역별 주요 개발계획으로<br>
                                    <span class="c-purple">온양을 중심으로 한 중앙권(행정, 문화, 상업)</span>, 음봉탕정을 중심으로 한<br>
                                    동부권(첨단산업), 인주/둔포를 중심으로 한 북부권(첨단융합산업)으로<br>
                                    <span class="c-red fw-b">인구증가 = 부동산가치상승</span>이 예상됩니다.<br>
                                </p>
                            </div>
                            <div class="picture type2 mt">
                                <img src="http://img.monopalace.com/landing/inquiry/plan/img_4.png" alt="아산시 산업단지">
                                <p class="text">
                                    아산 지역의 산업단지내 고용인원은 약 31,500여명이며
                                    <span class="c-blue">산업단지 내에는 삼성전자, 삼성SDI, 삼성디스플레이,
                                        LS산전,<br> 현대모비스 등 대기업이 대규 모로 분포</span>하고
                                    협력업체와 함께 입지하고 있습니다.
                                    대규모 산업단지 직장인과 인근 대학교 학생 및 교직원 등
                                    외부유입 세대는 꾸준히 증가하고 있습니다.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-plan-sec -->
            <section class="sec landing inquiry-location-sec" id="scroll_location">
                <div class="location-wrapper">
                    <div class="location-content group-f">
                        <h1 class="title-type1 title ta-left">
                            <img src="http://img.monopalace.com/landing/inquiry/location/title.png" alt="오시는 길">
                        </h1>
                        <div class="map-area">

                            <div class="map">
                                <div style="font:normal normal 400 12px/normal dotum, sans-serif; width:690px; height:442px; color:#333; position:relative"><div style="height: 410px;"><a href="https://map.kakao.com/?urlX=502379.0&amp;urlY=911577.0&amp;itemId=24286233&amp;q=%EB%AA%A8%EB%85%B8%ED%8C%B0%EB%A6%AC%EC%8A%A4&amp;srcid=24286233&amp;map_type=TYPE_MAP&amp;from=roughmap" target="_blank"><img class="map" src="//t1.daumcdn.net/roughmap/imgmap/27934bdb5c6533a47b5e1d231579bdc5ceeb1d580d7bd4c8cd6e31b317501d02" width="688px" height="408px" style="border:1px solid #ccc;"></a></div><div style="overflow: hidden; padding: 7px 11px; border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 0px 0px 2px 2px; background-color: rgb(249, 249, 249);"><a href="https://map.kakao.com" target="_blank" style="float: left;"><img src="//t1.daumcdn.net/localimg/localimages/07/2018/pc/common/logo_kakaomap.png" width="72" height="16" alt="카카오맵" style="display:block;width:72px;height:16px"></a><div style="float: right; position: relative; top: 1px; font-size: 11px;"><a target="_blank" href="https://map.kakao.com/?from=roughmap&amp;srcid=24286233&amp;confirmid=24286233&amp;q=%EB%AA%A8%EB%85%B8%ED%8C%B0%EB%A6%AC%EC%8A%A4&amp;rv=on" style="float:left;height:15px;padding-top:1px;line-height:15px;color:#000;text-decoration: none;">로드뷰</a><span style="width: 1px;padding: 0;margin: 0 8px 0 9px;height: 11px;vertical-align: top;position: relative;top: 2px;border-left: 1px solid #d0d0d0;float: left;"></span><a target="_blank" href="https://map.kakao.com/?from=roughmap&amp;eName=%EB%AA%A8%EB%85%B8%ED%8C%B0%EB%A6%AC%EC%8A%A4&amp;eX=502379.0&amp;eY=911577.0" style="float:left;height:15px;padding-top:1px;line-height:15px;color:#000;text-decoration: none;">길찾기</a><span style="width: 1px;padding: 0;margin: 0 8px 0 9px;height: 11px;vertical-align: top;position: relative;top: 2px;border-left: 1px solid #d0d0d0;float: left;"></span><a target="_blank" href="https://map.kakao.com?map_type=TYPE_MAP&amp;from=roughmap&amp;srcid=24286233&amp;itemId=24286233&amp;q=%EB%AA%A8%EB%85%B8%ED%8C%B0%EB%A6%AC%EC%8A%A4&amp;urlX=502379.0&amp;urlY=911577.0" style="float:left;height:15px;padding-top:1px;line-height:15px;color:#000;text-decoration: none;">지도 크게 보기</a></div></div></div>
                            </div>
                            <img src="http://img.monopalace.com/landing/inquiry/location/map_right.jpg" usemap="#map_right">
                            <div class="map-info">
                                <map name="map_right" id="map_right">
                                    <area shape="rect" coords="60,286,208,321" href="https://map.kakao.com/etc/print.jsp?c=502377.5,911576.25,3&t=m" target="_blank" alt="약도 출력">
                                    <area shape="rect" coords="214,286,363,320" href="http://map.daum.net/?urlX=502377&amp;urlY=911574&amp;urlLevel=3&amp;itemId=19782641&amp;q=%EB%AA%A8%EB%85%B8%ED%8C%B0%EB%A6%AC%EC%8A%A4&amp;srcid=19782641&amp;map_type=TYPE_MAP" target="_blank" alt="Daum 지도 바로가기">
                                </map>
                            </div>
                        </div>
                        <table class="location-tbl">
                            <colgroup>
                                <col style="width: 145px">
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th colspan="2">대중교통 이용시</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th height="50"><img src="http://img.monopalace.com/mono/dot.gif" alt="dot"> 지하철</th>
                                    <td>지하철 1호선 온양온천역 하차 (1번출구)후 도보 7분 거리 </td>
                                </tr>
                                <tr>
                                    <th><img src="http://img.monopalace.com/mono/dot.gif" alt="dot"> 시외버스</th>
                                    <td>송악나드리역 도보 1분 거리
                                        <p><img src="http://img.monopalace.com/info/bus_01.jpg" alt="일반">
                                            100 , 101 , 102 , 110 , 111 , 120 , 130 , 131 , 132 , 133 , 140 , 171 , 172 , 174 , 175 , 180 , 303, 810 , 820 , 830 , 840</p>
                                        <p style="margin-left:27px">900 , 901 , 910 , 911 , 920 , 921 , 930 , 931 </p>
                                        <img src="http://img.monopalace.com/info/bus_02.jpg" alt="좌석"> 500 , 501 , 510 , 511 , 512 , 530 , 531 , 540 , 560
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <!-- /.sec landing inquiry-location-sec -->
            <!-- <section class="sec landing inquiry-intro-sec">
        <div class="intro-wrapper">
            <div class="intro-content group-f">
                <h1 class="title-type1 title">
                    <img src="http://img.monopalace.com/landing/inquiry/intro/title.png" alt="">
                </h1>
                <p class="subtitle-type1 subtitle">

                </p>
                <figure class="picture ta-center">
                </figure>
            </div>
        </div>
    </section> -->
            <!-- /.sec landing inquiry-intro-sec -->
        </main>
        <!-- /.main.container -->

        <div id="footer" class="landing-footer cf">
            <div class="footer-area">
                <h2 class="logo"><img src="http://img.monopalace.com/footer_logo.gif" alt="모노팰리스"></h2>
                <address class="address">
                    대표이사 박기범 | 사업자등록번호 220-87-30865 | 서울특별시 강남구 테헤란로82길 15, 14층(대치동, 디아이타워)<br>
                    COPYRIGHT 2014 BY <span class="footer_mono">MONOPALACE</span> CO.,LTD ALL RIGHTS RESERVED. <br>
                    <img src="http://img.monopalace.com/footer_text.gif" alt="본 홈페이지에 사용된 투시도 및 이미지 등은 소비자의 이해를 돕기 위한 이미지 컷으로 차이가 있습니다. 하자 등에 대한 피해보상은 주택업 관련 규정에 따라 적용됩니다.">
                </address>
            </div>
        </div>

        <div class="bottombar-wrapper" id="bottombarWrapper" style="display: none">
            <div class="title-area">
                <div class="lg-wd">
                    <img src="http://img.monopalace.com/landing/inquiry/bottombar_title.png" alt="5년간 임대수익보장!" class="bar-title">
                    <a href="#scroll_sale" class="bar-btn"><img src="http://img.monopalace.com/landing/inquiry/bottombar_btn.png" alt="분양혜택확인"></a>
                </div>
            </div>
            <div class="number-area">
                <img src="http://img.monopalace.com/landing/inquiry/bottombar_inquiry_number.png" alt="분양문의 041 585 0003">
            </div>
        </div>

        <aside class="quick-menu" id="quickMenu" style="display: none">
            <h2 class="logo"><img src="http://img.monopalace.com/landing/inquiry/logo.png" alt="모노팰리스"></h2>
            <ul class="menu">
                <li><a href="#scroll_premier"><span class="in">프리미엄<br>입지환경</span></a></li>
                <li><a href="#scroll_sale"><span class="in">임대수익</span></a></li>
                <li><a href="#scroll_intro"><span class="in">상품소개</span></a></li>
                <li><a href="#scroll_type"><span class="in">타입 및 특화시설</span></a></li>
                <li><a href="#scroll_plan"><span class="in">지역개발호재</span></a></li>
                <li><a href="#scroll_location"><span class="in">오시는 길</span></a></li>
                <li class="inquiry"><a href="javascript:;" data-toggle="modal" data-target="inquiry-modal"><span class="in">온라인 문의</span></a></li>
            </ul>
            <div class="top-btn-cover">
                <a href="javascript:;" class="top-btn" role="button" onclick="$( 'html, body' ).animate( { scrollTop : 0 }, 400 )">
                    <img src="http://img.monopalace.com/landing/inquiry/go_top.png" alt="위로">
                </a>
            </div>
        </aside>

        <div class="modal modal-mask scale-no bg-no inquiry-modal" role="dialog" aira-modal="true" tabindex="-1">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <form action="./inquiry_action.php" method="POST">
                        <div class="modal-header">
                            <img src="http://img.monopalace.com/landing/inquiry/modal/inquiry_title.png" alt="모노팰리스 분양문의">
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>이름</label>
                                <input type="text" name="name" class="form-input name">
                            </div>
                            <div class="form-group">
                                <label>연락처</label>
                                <input type="text" name="phone" class="form-input phone" placeholder="- 없이 입력해주세요.">
                            </div>
                            <div class="form-group">
                                <label>문의내용</label>
                                <textarea name="content" cols="30" rows="10" class="form-textarea" placeholder="문의사항을 간략하게 작성해주시면 전화로 안내드리겠습니다."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="ap-reset inquiry-btn">문의하기</button>
                            <a href="javascript:;" data-dismiss="modal" class="close-btn">
                                <span class="x-cover"><span class="x">&times;</span></span>
                                <span class="txt">닫기</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.inquiry-modal -->

    </div>
    <!-- /#wrap -->

    <!-- script -->
    <!-- <script src="https://kit.fontawesome.com/3781a5bb61.js"></script> -->
    <!-- <script src="http://static.monopalace.com/js/libs/jquery.easing.min.js"></script> -->
    <!-- <script src="http://static.monopalace.com/js/libs/waypoints.min.js"></script> -->
    <!-- <script src="http://static.monopalace.com/js/libs/slick.min.js"></script> -->
    <script src="http://static.monopalace.com/js/libs/jquery.prettyPhoto.js"></script>

    <!-- http://static.monopalace.com/ -->
    <script type="text/javascript" src="http://static.monopalace.com/js/script.js"></script>
    <script type="text/javascript" src="http://static.monopalace.com/js/ui.js"></script>

    <!--[if lt IE 9]><script type="text/javascript" src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script> <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script> <![endif]-->

</body>

</html>