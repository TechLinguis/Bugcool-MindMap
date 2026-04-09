<?php
$pageTitle = '测试结果';
include_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/database.php';

$cert = null;
$certNo = $_GET['cert'] ?? '';

if ($certNo) {
    $db = Database::getInstance();
    $cert = $db->getCertificateByNo($certNo);
    if ($cert) {
        $cert['scores'] = json_decode($cert['scores'], true);
        $cert['strengths'] = json_decode($cert['strengths'], true) ?: [];
        $cert['weaknesses'] = json_decode($cert['weaknesses'], true) ?: [];
        $cert['careers'] = json_decode($cert['careers'], true) ?: [];
        $cert['celebrities'] = json_decode($cert['celebrities'], true) ?: [];
    }
}

if (!$cert) {
    echo '<div class="container py-5 text-center"><div class="card-mbti p-5"><i class="bi bi-exclamation-circle" style="font-size:3rem;color:var(--rose);"></i><h3 class="fw-bold mt-3 mb-3">未找到证书</h3><p style="color:var(--text-2);">证书编号无效或不存在</p><a href="query.php" class="btn btn-primary-mbti mt-3">查询证书</a></div></div>';
    include_once __DIR__ . '/includes/footer.php';
    exit;
}

$scores = $cert['scores'];
$color = $cert['type_color'];
$icon = $cert['icon'];

function calcPercent($a, $b) {
    $total = $a + $b;
    return $total > 0 ? round(($a / $total) * 100) : 50;
}
$eiPct = calcPercent($scores['E'], $scores['I']);
$snPct = calcPercent($scores['S'], $scores['N']);
$tfPct = calcPercent($scores['T'], $scores['F']);
$jpPct = calcPercent($scores['J'], $scores['P']);
$eiLeft = $scores['E'] >= $scores['I'] ? 'E' : 'I';
$snLeft = $scores['S'] >= $scores['N'] ? 'S' : 'N';
$tfLeft = $scores['T'] >= $scores['F'] ? 'T' : 'F';
$jpLeft = $scores['J'] >= $scores['P'] ? 'J' : 'P';

$shareUrl = SITE_URL . '/result.php?cert=' . urlencode($cert['certificate_no']);

// ============ 维度详解卡片数据 ============
$dimensionDetails = [
    'EI' => [
        'color' => '#818CF8',
        'icon' => 'bi-chat-dots-fill',
        'name' => '能量方向',
        'left' => ['letter' => 'E', 'label' => '外向 Extraversion', 'desc' => '从与人互动中获取能量，喜欢社交活动'],
        'right' => ['letter' => 'I', 'label' => '内向 Introversion', 'desc' => '从独处和内省中获取能量，偏好深度思考'],
    ],
    'SN' => [
        'color' => '#22D3EE',
        'icon' => 'bi-eye-fill',
        'name' => '信息获取',
        'left' => ['letter' => 'S', 'label' => '感觉 Sensing', 'desc' => '关注具体事实和细节，依赖五官感知'],
        'right' => ['letter' => 'N', 'label' => '直觉 Intuition', 'desc' => '关注整体模式和可能性，依赖第六感'],
    ],
    'TF' => [
        'color' => '#F472B6',
        'icon' => 'bi-heart-pulse-fill',
        'name' => '决策方式',
        'left' => ['letter' => 'T', 'label' => '思维 Thinking', 'desc' => '基于逻辑和客观分析做决定'],
        'right' => ['letter' => 'F', 'label' => '情感 Feeling', 'desc' => '基于价值观和对他人影响做决定'],
    ],
    'JP' => [
        'color' => '#FBBF24',
        'icon' => 'bi-compass-fill',
        'name' => '生活方式',
        'left' => ['letter' => 'J', 'label' => '判断 Judging', 'desc' => '喜欢有计划有秩序，提前安排好一切'],
        'right' => ['letter' => 'P', 'label' => '感知 Perceiving', 'desc' => '喜欢灵活随性，保持开放和适应'],
    ],
];

// ============ 兼容性数据 ============
$compatibility = [
    'INTJ'=>['best'=>['ENFP','ENTP'],'good'=>['INFJ','INTJ'],'grow'=>['ESFP','ESTP']],
    'INTP'=>['best'=>['ENTJ','ENFJ'],'good'=>['INTP','INFP'],'grow'=>['ESFJ','ESTJ']],
    'ENTJ'=>['best'=>['INTP','INFP'],'good'=>['ENTJ','ENFJ'],'grow'=>['INFP','ISFP']],
    'ENTP'=>['best'=>['INTJ','INFJ'],'good'=>['ENTP','ENFP'],'grow'=>['ISFJ','ISTJ']],
    'INFJ'=>['best'=>['ENTP','ENFP'],'good'=>['INFJ','INTJ'],'grow'=>['ESTP','ESFP']],
    'INFP'=>['best'=>['ENTJ','INTJ'],'good'=>['ENFP','INFP'],'grow'=>['ESTJ','ESFJ']],
    'ENFJ'=>['best'=>['INTP','INFP'],'good'=>['ENFJ','ENTJ'],'grow'=>['ISTP','ISFP']],
    'ENFP'=>['best'=>['INTJ','INFJ'],'good'=>['ENFP','ENTP'],'grow'=>['ISTJ','ISFJ']],
    'ISTJ'=>['best'=>['ESFP','ESTP'],'good'=>['ISFJ','ISTJ'],'grow'=>['ENFP','ENTP']],
    'ISFJ'=>['best'=>['ESTP','ESFP'],'good'=>['ISTJ','ISFJ'],'grow'=>['ENTP','ENFP']],
    'ESTJ'=>['best'=>['ISFP','ISTP'],'good'=>['ESFJ','ESTJ'],'grow'=>['INTP','INFP']],
    'ESFJ'=>['best'=>['ISFP','ISTP'],'good'=>['ESTJ','ESFJ'],'grow'=>['INTP','INTJ']],
    'ISTP'=>['best'=>['ESTJ','ESFJ'],'good'=>['ISTP','ISFP'],'grow'=>['ENFJ','ENTJ']],
    'ISFP'=>['best'=>['ESTJ','ESFJ'],'good'=>['ISFP','ISTP'],'grow'=>['ENFJ','ENTJ']],
    'ESTP'=>['best'=>['ISTJ','ISFJ'],'good'=>['ESTP','ESFP'],'grow'=>['INFJ','INTJ']],
    'ESFP'=>['best'=>['ISTJ','ISFJ'],'good'=>['ESTP','ESFP'],'grow'=>['INFJ','INTJ']],
];

$allTypesInfo = [
    'INTJ'=>['name'=>'建筑师','nickname'=>'独立思考者','icon'=>'🧱','color'=>'#818CF8'],
    'INTP'=>['name'=>'逻辑学家','nickname'=>'思想探险家','icon'=>'🔬','color'=>'#22D3EE'],
    'ENTJ'=>['name'=>'指挥官','nickname'=>'天生领袖','icon'=>'👑','color'=>'#818CF8'],
    'ENTP'=>['name'=>'辩论家','nickname'=>'挑战者','icon'=>'💡','color'=>'#818CF8'],
    'INFJ'=>['name'=>'提倡者','nickname'=>'理想主义者','icon'=>'🦋','color'=>'#A78BFA'],
    'INFP'=>['name'=>'调停者','nickname'=>'治愈者','icon'=>'🌸','color'=>'#2DD4BF'],
    'ENFJ'=>['name'=>'主人公','nickname'=>'引导者','icon'=>'⭐','color'=>'#2DD4BF'],
    'ENFP'=>['name'=>'竞选者','nickname'=>'自由灵魂','icon'=>'🎨','color'=>'#C084FC'],
    'ISTJ'=>['name'=>'检查官','nickname'=>'守护者','icon'=>'📋','color'=>'#818CF8'],
    'ISFJ'=>['name'=>'守护者','nickname'=>'保护者','icon'=>'🛡️','color'=>'#34D399'],
    'ESTJ'=>['name'=>'总经理','nickname'=>'组织者','icon'=>'📊','color'=>'#F87171'],
    'ESFJ'=>['name'=>'执政官','nickname'=>'和谐者','icon'=>'🤝','color'=>'#FBBF24'],
    'ISTP'=>['name'=>'鉴赏家','nickname'=>'灵巧大师','icon'=>'🔧','color'=>'#FBBF24'],
    'ISFP'=>['name'=>'探险家','nickname'=>'冒险家','icon'=>'🌿','color'=>'#F472B6'],
    'ESTP'=>['name'=>'企业家','nickname'=>'行动派','icon'=>'🚀','color'=>'#FB923C'],
    'ESFP'=>['name'=>'表演者','nickname'=>'生活家','icon'=>'🎭','color'=>'#F472B6'],
];

// ============ 工作风格数据 ============
$workStyles = [
    'INTJ'=>['style'=>'战略性规划者，善于制定长远目标和系统性方案','env'=>'独立、安静、需要深度思考的环境','strength'=>'能处理复杂问题并找到创新解决方案'],
    'INTP'=>['style'=>'分析型思考者，喜欢探索理论和可能性','env'=>'灵活、自主、 intellectually stimulating','strength'=>'善于发现逻辑漏洞和提出独到见解'],
    'ENTJ'=>['style'=>'果断的领导者，善于组织和驱动团队达成目标','env'=>'快节奏、有挑战性、能发挥领导力','strength'=>'天生具有决策力和执行力'],
    'ENTP'=>['style'=>'创新型探索者，善于发现和挑战现有模式','env'=>'充满变化、鼓励创新的动态环境','strength'=>'能从多角度看待问题并提出突破性想法'],
    'INFJ'=>['style'=>'有远见的理想主义者，善于理解他人深层需求','env'=>'有意义的工作、能帮助他人的环境','strength'=>'能洞察人心并建立深度关系'],
    'INFP'=>['style'=>'富有创造力的调停者，追求内心价值和个人意义','env'=>'自由、富有创造空间的和谐环境','strength'=>'善于理解复杂情感并创造性地表达想法'],
    'ENFJ'=>['style'=>'富有感染力的引导者，善于激励和培养他人','env'=>'团队协作、能发挥影响力的环境','strength'=>'天生的沟通者和团队凝聚力缔造者'],
    'ENFP'=>['style'=>'充满热情的创意家，善于发现新的可能性','env'=>'多元、灵活、鼓励社交的环境','strength'=>'能感染他人并推动变革'],
    'ISTJ'=>['style'=>'可靠的组织者，注重细节和规则的执行','env'=>'有序、稳定、职责明确的环境','strength'=>'极高的可靠性和执行力'],
    'ISFJ'=>['style'=>'温暖的支持者，默默为他人付出和守护','env'=>'和谐、稳定、被需要的环境','strength'=>'出色的记忆力和对细节的关注'],
    'ESTJ'=>['style'=>'高效的执行者，善于建立和维护秩序','env'=>'结构化、目标导向的环境','strength'=>'强大的组织能力和领导力'],
    'ESFJ'=>['style'=>'贴心的协调者，善于维护人际关系和团队和谐','env'=>'社交活跃、能帮助他人的环境','strength'=>'出色的人际关系维护和团队凝聚力'],
    'ISTP'=>['style'=>'灵活的实践者，善于动手解决问题','env'=>'自主、实际、允许灵活操作的环境','strength'=>'出色的危机处理和问题解决能力'],
    'ISFP'=>['style'=>'敏感的艺术家，追求和谐与美','env'=>'自由、有创造空间、贴近自然的环境','strength'=>'对美有独特的感知力和表达力'],
    'ESTP'=>['style'=>'精力充沛的行动派，善于把握当下机会','env'=>'快节奏、充满挑战的实际环境','strength'=>'出色的适应力和即兴发挥能力'],
    'ESFP'=>['style'=>'热情的表演者，善于享受当下和感染他人','env'=>'充满乐趣、社交活跃的动态环境','strength'=>'天生的娱乐者和氛围营造者'],
];

// ============ 学习偏好数据 ============
$learningPrefs = [
    'INTJ'=>['method'=>'独立研究、阅读专业文献、系统性学习','prefer'=>'概念框架、理论模型、独立项目','tips'=>'给自己设定学习目标和进度计划'],
    'INTP'=>['method'=>'探索性学习、讨论辩论、深度研究','prefer'=>'复杂理论、抽象概念、开放性问题','tips'=>'避免过度追求完美，先完成再完善'],
    'ENTJ'=>['method'=>'目标导向学习、领导讨论、实践应用','prefer'=>'案例分析、管理理论、领导力培养','tips'=>'适当放慢节奏，关注细节和理论基础'],
    'ENTP'=>['method'=>'多元探索、头脑风暴、跨领域学习','prefer'=>'创新方法、实验项目、辩论讨论','tips'=>'保持专注，避免同时开始太多项目'],
    'INFJ'=>['method'=>'深度阅读、反思笔记、一对一讨论','prefer'=>'有意义的内容、人文社科、心理学','tips'=>'给自己足够的独处时间来消化所学'],
    'INFP'=>['method'=>'创意表达、主题探索、写日记','prefer'=>'有情感共鸣的内容、艺术、文学','tips'=>'将学习与个人价值观联系起来'],
    'ENFJ'=>['method'=>'小组学习、教学相长、实践应用','prefer'=>'团队项目、社交学习、案例讨论','tips'=>'在帮助他人的同时也要照顾自己'],
    'ENFP'=>['method'=>'兴趣驱动、体验式学习、多样化输入','prefer'=>'有趣的内容、创新方法、互动讨论','tips'=>'制定计划保持学习连贯性，避免三分热度'],
    'ISTJ'=>['method'=>'系统学习、按部就班、重复练习','prefer'=>'结构化内容、具体事实、操作指南','tips'=>'尝试融入更多灵活和创造性思维'],
    'ISFJ'=>['method'=>'实践操作、笔记整理、帮助他人学习','prefer'=>'具体步骤、实用技能、温馨的学习环境','tips'=>'勇于尝试新方法，不必过度依赖既有经验'],
    'ESTJ'=>['method'=>'目标导向、高效执行、标准流程','prefer'=>'清晰结构、实践应用、可衡量的结果','tips'=>'多听取不同意见，培养灵活性'],
    'ESFJ'=>['method'=>'小组协作、互动讨论、实践练习','prefer'=>'社交学习、团队项目、即时反馈','tips'=>'也要给自己独处学习和反思的时间'],
    'ISTP'=>['method'=>'动手实践、自主探索、解决实际问题','prefer'=>'实际操作、技术手册、实验项目','tips'=>'适当记录和总结学习心得'],
    'ISFP'=>['method'=>'体验式学习、感官参与、自我节奏','prefer'=>'有美感的内容、自然探索、手工制作','tips'=>'尝试结构化学习以提升学习效率'],
    'ESTP'=>['method'=>'即兴体验、实地考察、竞争性学习','prefer'=>'实际操作、快速反馈、刺激性内容','tips'=>'培养耐心，投入需要长期积累的学习'],
    'ESFP'=>['method'=>'社交互动、游戏化学习、感官体验','prefer'=>'有趣的活动、团队合作、即时奖励','tips'=>'制定学习计划，培养持续学习的习惯'],
];

// ============ 团队角色数据 ============
$teamRoles = [
    'INTJ'=>['role'=>'战略规划师','desc'=>'负责制定团队长期战略和方向，提供深度分析和创新方案','strengths'=>['深度分析','战略思维','独立工作']],
    'INTP'=>['role'=>'创新顾问','desc'=>'团队的问题解决专家，善于发现新的可能性并提出独特方案','strengths'=>['逻辑分析','创新思维','知识渊博']],
    'ENTJ'=>['role'=>'团队领导','desc'=>'天生的领导者，能高效组织资源并驱动团队达成目标','strengths'=>['领导力','决策力','执行力']],
    'ENTP'=>['role'=>'创意推动者','desc'=>'团队的创新引擎，不断挑战现状并推动变革','strengths'=>['创造力','说服力','适应性']],
    'INFJ'=>['role'=>'文化塑造者','desc'=>'塑造团队愿景和文化，帮助成员找到工作的意义和方向','strengths'=>['洞察力','同理心','愿景规划']],
    'INFP'=>['role'=>'创意调和者','desc'=>'在团队中促进理解和谐，用创造力为团队注入灵感','strengths'=>['创造力','同理心','理想主义']],
    'ENFJ'=>['role'=>'团队凝聚者','desc'=>'激励团队成员、培养人才、维护团队和谐','strengths'=>['领导力','沟通力','培养人才']],
    'ENFP'=>['role'=>'头脑风暴者','desc'=>'团队的灵感源泉，善于激发创意和推动团队活力','strengths'=>['创造力','感染力','灵活性']],
    'ISTJ'=>['role'=>'质量守护者','desc'=>'确保项目质量和规范执行，维护团队的工作标准','strengths'=>['可靠性','组织力','注重细节']],
    'ISFJ'=>['role'=>'团队守护者','desc'=>'默默支持团队成员，维护团队和谐并提供后勤保障','strengths'=>['支持性','可靠性','注重细节']],
    'ESTJ'=>['role'=>'执行主管','desc'=>'高效管理团队运营，确保任务按时完成和流程顺畅','strengths'=>['组织力','执行力','领导力']],
    'ESFJ'=>['role'=>'团队协调员','desc'=>'协调团队成员关系，营造积极的团队氛围','strengths'=>['协调力','支持性','社交能力']],
    'ISTP'=>['role'=>'技术专家','desc'=>'在关键时刻提供技术支持和实用解决方案','strengths'=>['技术能力','问题解决','适应力']],
    'ISFP'=>['role'=>'和谐调解者','desc'=>'用温和的方式化解冲突，为团队带来美感和平衡','strengths'=>['调和力','创造性','适应性']],
    'ESTP'=>['role'=>'行动先锋','desc'=>'在压力下快速行动，带领团队应对紧急情况','strengths'=>['行动力','应变力','说服力']],
    'ESFP'=>['role'=>'团队激励者','desc'=>'活跃团队气氛，在困难时保持积极乐观的态度','strengths'=>['感染力','适应性','社交能力']],
];

// ============ 人际关系风格数据 ============
$relationshipStyles = [
    'INTJ'=>['label'=>'深度连接型','desc'=>'重视深度而有质量的少数关系，喜欢能进行思想交流的伙伴。对社交活动选择性参与，更倾向于一对一的深度对话而非大群体社交。'],
    'INTP'=>['label'=>'思想共鸣型','desc'=>'寻求能进行智识交流和思想碰撞的伙伴。社交能量有限但珍贵，更看重对话的深度和趣味性。'],
    'ENTJ'=>['label'=>'目标驱动型','desc'=>'将社交视为建立人脉和推动目标的工具。喜欢与有进取心和能力的人交往，在关系中往往扮演领导角色。'],
    'ENTP'=>['label'=>'兴趣导向型','desc'=>'社交风格充满活力和好奇，喜欢与不同类型的人交流。对话中善于转换话题和挑战观点。'],
    'INFJ'=>['label'=>'心灵契合型','desc'=>'追求深层的精神连接和理解。虽然社交能量有限，但对真正重要的人会投入全部的关心和热情。'],
    'INFP'=>['label'=>'真诚表达型','desc'=>'在人际关系中追求真实和深度的连接。对表面化的社交感到不适，但在信任的人面前会展现出丰富的内心世界。'],
    'ENFJ'=>['label'=>'温暖引导型','desc'=>'天生善于理解他人并建立情感连接。经常是朋友圈中的精神支柱和问题解决者。'],
    'ENFP'=>['label'=>'热情感染型','desc'=>'社交能力出色，善于让身边的人感到快乐和被关注。对不同类型的人都充满好奇和热情。'],
    'ISTJ'=>['label'=>'可靠稳定型','desc'=>'重视承诺和责任，对家人和朋友极度忠诚。虽然不善表达情感，但会用实际行动来表达关心。'],
    'ISFJ'=>['label'=>'温暖守护型','desc'=>'默默关注他人的需求并主动提供帮助。重视传统和稳定的关系，是朋友圈中最可靠的倾听者。'],
    'ESTJ'=>['label'=>'责任担当型','desc'=>'在关系中注重责任和义务，善于组织家庭和社交活动。直接坦诚的沟通风格，重视传统价值观。'],
    'ESFJ'=>['label'=>'和谐维护型','desc'=>'极度重视社交和谐，善于维护各种人际关系。是聚会的组织者和矛盾调解者。'],
    'ISTP'=>['label'=>'轻松独立型','desc'=>'在关系中保持独立空间，不黏人也不需要太多关注。用自己的方式（行动而非语言）来表达关心。'],
    'ISFP'=>['label'=>'温柔随和型','desc'=>'追求和谐的人际关系，不喜欢冲突。用温暖和包容来维护关系，有强大的共情能力。'],
    'ESTP'=>['label'=>'活力社交型','desc'=>'喜欢在社交中成为焦点，善于用幽默和活力感染他人。在关系中追求刺激和新鲜感。'],
    'ESFP'=>['label'=>'快乐分享型','desc'=>'社交中心人物，善于让周围的人感到快乐。用自发的热情和慷慨来建立和维护关系。'],
];

// ============ 改善建议数据 ============
$improvementTips = [
    'INTJ'=>['tips'=>['学会更多地关注他人的感受，在表达观点时注意语气','尝试接受"不完美"的方案，有时完成比完美更重要','定期与亲密的人进行非工作性质的交流','允许自己放松和享受当下，不必时刻规划未来']],
    'INTP'=>['tips'=>['将想法付诸行动，避免陷入无休止的理论思考','学会在社交中更加主动和开放','设定明确的截止日期来克服拖延倾向','关注身体健康和日常生活规律']],
    'ENTJ'=>['tips'=>['学会倾听他人意见，并非所有事都需要你来主导','关注团队成员的情感需求，而不只是任务结果','给自己留出放松和反思的时间','接受他人的工作方式可能与你不同']],
    'ENTP'=>['tips'=>['培养专注力和持久性，完成已开始的项目','在做决定时考虑对他人的影响','学会欣赏和维持已有的美好事物','减少不必要的辩论，有时候和谐比正确更重要']],
    'INFJ'=>['tips'=>['学会说"不"，不必对每个人的需求都负责','关注自己的需求，不要总是把自己放在最后','接受现实世界的不完美，理想与现实之间需要妥协','在疲惫时允许自己独处充电']],
    'INFP'=>['tips'=>['培养实际执行能力，将理想转化为具体行动','学会接受批评，不要把每次反馈都当作否定','建立日常规律，避免过度沉浸在幻想中','在追求完美的同时也要接纳"足够好"']],
    'ENFJ'=>['tips'=>['关注自己的需求，不要过度为他人的情绪负责','学会接受不是所有人都能被你帮助或改变','给自己留出独处时间来恢复能量','在帮助他人之前先确保自己的状态良好']],
    'ENFP'=>['tips'=>['培养专注力和坚持完成项目的能力','建立日常规律和时间管理习惯','在面对困难时不要轻易放弃或转向新方向','学会深入而非广泛地发展技能和关系']],
    'ISTJ'=>['tips'=>['尝试接受变化和不确定性，并非所有事都能提前计划','更加开放地考虑新的方法和观点','在关注事实的同时也关注他人的情感','适当地表达自己的感受和需求']],
    'ISFJ'=>['tips'=>['学会表达自己的需求和不满，不必总是默默承受','尝试新事物，不要总是依赖熟悉的方式','认识到帮助他人不是你唯一的价值来源','给自己留出独处时间和个人空间']],
    'ESTJ'=>['tips'=>['学会倾听不同的意见，接受多种做事方式','在追求效率的同时关注团队成员的感受','适当放慢节奏，享受过程而非只关注结果','培养灵活性和适应性']],
    'ESFJ'=>['tips'=>['学会独立做决定，不必总是寻求他人的认可','接受不是所有人都喜欢你或需要你的帮助','给自己独处的时间和空间来恢复能量','在关心他人时也要关注自己的需求']],
    'ISTP'=>['tips'=>['学会表达自己的感受，不要总是沉默','对长期承诺保持开放态度','在关注技术细节的同时也关注人际关系','培养耐心，有些事情需要时间的积累']],
    'ISFP'=>['tips'=>['学会直接表达自己的想法和需求','在面对冲突时更加果断和坚定','设定并追求可衡量的目标','在享受当下的同时也要为未来做打算']],
    'ESTP'=>['tips'=>['学会思考和计划，不要总是冲动行事','关注他人的感受和长远影响','培养耐心和对长期项目的承诺','在追求刺激的同时也关注内在成长']],
    'ESFP'=>['tips'=>['学会规划和管理财务','培养独处和反思的能力','在面对困难时坚持而不是逃避','在享受社交的同时也要留出深度关系的时间']],
];

// ============ 同类型名人墙数据 ============
$famousPeople = [
    'INTJ'=>[
        ['name'=>'尼古拉·特斯拉','role'=>'发明家、电气工程师','desc'=>'交流电系统之父，拥有超过300项专利'],
        ['name'=>'埃隆·马斯克','role'=>'企业家、工程师','desc'=>'特斯拉、SpaceX创始人，推动人类跨星际文明'],
        ['name'=>'克里斯托弗·诺兰','role'=>'电影导演','desc'=>'《盗梦空间》《星际穿越》等经典电影导演'],
        ['name'=>'马克·扎克伯格','role'=>'科技企业家','desc'=>'Facebook联合创始人，社交媒体先驱'],
        ['name'=>'弗里德里希·尼采','role'=>'哲学家','desc'=>'"上帝已死"的提出者，存在主义哲学先驱'],
        ['name'=>'艾萨克·牛顿','role'=>'物理学家、数学家','desc'=>'万有引力定律发现者，经典力学奠基人'],
    ],
    'INTP'=>[
        ['name'=>'阿尔伯特·爱因斯坦','role'=>'物理学家','desc'=>'相对论之父，现代物理学奠基人'],
        ['name'=>'比尔·盖茨','role'=>'科技企业家','desc'=>'微软联合创始人，改变个人电脑时代'],
        ['name'=>'玛丽·居里','role'=>'物理学家、化学家','desc'=>'放射性研究先驱，两届诺贝尔奖获得者'],
        ['name'=>'查尔斯·达尔文','role'=>'生物学家','desc'=>'进化论提出者，改变人类对生命的认知'],
        ['name'=>'亚伯拉罕·林肯','role'=>'美国总统','desc'=>'废除奴隶制，维护美国统一'],
        ['name'=>'拉里·佩奇','role'=>'科技企业家','desc'=>'Google联合创始人，搜索引擎革命'],
    ],
    'ENTJ'=>[
        ['name'=>'史蒂夫·乔布斯','role'=>'企业家','desc'=>'苹果公司联合创始人，科技设计革命者'],
        ['name'=>'朱莉娅·罗伯茨','role'=>'演员','desc'=>'好莱坞巨星，奥斯卡最佳女主角'],
        ['name'=>'拿破仑·波拿巴','role'=>'军事家、政治家','desc'=>'法国皇帝，改变欧洲历史格局'],
        ['name'=>'玛格丽特·撒切尔','role'=>'政治家','desc'=>'英国首位女首相，"铁娘子"'],
        ['name'=>'杰夫·贝索斯','role'=>'企业家','desc'=>'亚马逊创始人，电商革命推动者'],
        ['name'=>'杰克·韦尔奇','role'=>'企业家','desc'=>'通用电气前CEO，管理学大师'],
    ],
    'ENTP'=>[
        ['name'=>'本杰明·富兰克林','role'=>'发明家、政治家','desc'=>'美国开国元勋之一，多项发明创造'],
        ['name'=>'小罗伯特·唐尼','role'=>'演员','desc'=>'钢铁侠扮演者，好莱坞 comeback 传奇'],
        ['name'=>'托马斯·爱迪生','role'=>'发明家','desc'=>'电灯泡发明者，拥有超过1000项专利'],
        ['name'=>'马克·吐温','role'=>'作家','desc'=>'美国文学之父，《汤姆·索亚历险记》作者'],
        ['name'=>'莎拉·西尔弗曼','role'=>'喜剧演员','desc'=>'以大胆幽默著称的脱口秀演员'],
        ['name'=>'席琳·狄翁','role'=>'歌手','desc'=>'《泰坦尼克号》主题曲演唱者，全球销量超2亿'],
    ],
    'INFJ'=>[
        ['name'=>'马丁·路德·金','role'=>'民权运动领袖','desc'=>"I Have a Dream 演讲者，非暴力抗争先驱"],
        ['name'=>'纳尔逊·曼德拉','role'=>'政治家','desc'=>'南非首位黑人总统，反种族隔离运动领袖'],
        ['name'=>'特蕾莎修女','role'=>'人道主义者','desc'=>'诺贝尔和平奖获得者，一生服务贫困者'],
        ['name'=>'歌德','role'=>'诗人、剧作家','desc'=>'德国文学巨匠，《少年维特之烦恼》作者'],
        ['name'=>'陀思妥耶夫斯基','role'=>'小说家','desc'=>'《罪与罚》《卡拉马佐夫兄弟》作者'],
        ['name'=>'J·K·罗琳','role'=>'作家','desc'=>'《哈利·波特》系列作者，魔法世界缔造者'],
    ],
    'INFP'=>[
        ['name'=>'威廉·莎士比亚','role'=>'剧作家、诗人','desc'=>'英国文学史上最杰出的作家'],
        ['name'=>'奥黛丽·赫本','role'=>'演员、人道主义者','desc'=>'《罗马假日》主演，联合国亲善大使'],
        ['name'=>'约翰·列侬','role'=>'音乐家','desc'=>'披头士乐队成员，和平运动倡导者'],
        ['name'=>'艾米莉·狄金森','role'=>'诗人','desc'=>'美国伟大诗人，生前几乎匿名发表'],
        ['name'=>'托尔金','role'=>'作家','desc'=>'《魔戒》系列作者，奇幻文学之父'],
        ['name'=>'大卫·鲍伊','role'=>'音乐家','desc'=>'摇滚传奇，不断重塑自我的艺术大师'],
    ],
    'ENFJ'=>[
        ['name'=>'贝拉克·奥巴马','role'=>'美国总统','desc'=>'美国首位非裔总统，变革的象征'],
        ['name'=>'奥普拉·温弗瑞','role'=>'媒体企业家','desc'=>'脱口秀女王，全球最具影响力的女性之一'],
        ['name'=>'马丁·路德·金','role'=>'民权领袖','desc'=>'非暴力运动领袖，诺贝尔和平奖获得者'],
        ['name'=>'本·阿弗莱克','role'=>'演员、导演','desc'=>'奥斯卡最佳导演，好莱坞多面手'],
        ['name'=>'詹妮弗·劳伦斯','role'=>'演员','desc'=>'奥斯卡最年轻最佳女主角之一'],
        ['name'=>'罗宾·威廉姆斯','role'=>'演员、喜剧家','desc'=>'好莱坞传奇，以温暖和幽默著称'],
    ],
    'ENFP'=>[
        ['name'=>'罗宾·威廉姆斯','role'=>'演员、喜剧家','desc'=>'天生的表演者，能驾驭各种角色'],
        ['name'=>'Walt Disney','role'=>'动画家、企业家','desc'=>'迪士尼创始人，创造梦幻王国'],
        ['name'=>'小罗伯特·唐尼','role'=>'演员','desc'=>'从低谷到巅峰的传奇，钢铁侠扮演者'],
        ['name'=>'安妮·海瑟薇','role'=>'演员','desc'=>'奥斯卡影后，《悲惨世界》主演'],
        ['name'=>'威尔·史密斯','role'=>'演员','desc'=>'好莱坞巨星，歌手兼演员多栖发展'],
        ['name'=>'丹尼·德维托','role'=>'演员、导演','desc'=>'好莱坞实力派，以幽默角色著称'],
    ],
    'ISTJ'=>[
        ['name'=>'乔治·华盛顿','role'=>'美国总统','desc'=>'美国开国总统，国家基石'],
        ['name'=>'安格拉·默克尔','role'=>'政治家','desc'=>'德国总理，欧洲最具影响力领导人之一'],
        ['name'=>'沃伦·巴菲特','role'=>'投资家','desc'=>'股神，伯克希尔·哈撒韦CEO'],
        ['name'=>'辛迪·克劳馥','role'=>'超模','desc'=>'90年代超级名模，商业女强人'],
        ['name'=>'杰夫·贝索斯','role'=>'企业家','desc'=>'亚马逊创始人，全球电商变革者'],
        ['name'=>'西奥多·罗斯福','role'=>'美国总统','desc'=>'最年轻的美国总统，进步时代改革者'],
    ],
    'ISFJ'=>[
        ['name'=>'碧昂丝','role'=>'歌手','desc'=>'全球流行天后，格莱美获奖最多女歌手'],
        ['name'=>'伊丽莎白女王二世','role'=>'君主','desc'=>'英国在位时间最长的君主，国家象征'],
        ['name'=>'阿姆','role'=>'说唱歌手','desc'=>'用音乐讲述真实故事，影响一代人'],
        ['name'=>'凯特王妃','role'=>'王室成员','desc'=>'英国王妃，以亲和力著称'],
        ['name'=>'安妮女王','role'=>'英国女王','desc'=>'大英帝国时期的重要君主'],
        ['name'=>'林赛·罗韩','role'=>'演员','desc'=>'童星出身，好莱坞演员'],
    ],
    'ESTJ'=>[
        ['name'=>'希拉里·克林顿','role'=>'政治家','desc'=>'美国国务卿，首位女性总统候选人'],
        ['name'=>'亨利·福特','role'=>'企业家','desc'=>'福特汽车创始人，流水线生产革命'],
        ['name'=>'米歇尔·奥巴马','role'=>'第一夫人','desc'=>'美国前第一夫人，女性权益倡导者'],
        ['name'=>'科林·鲍威尔','role'=>'将军、政治家','desc'=>'美国国务卿，军事战略家'],
        ['name'=>'索妮娅·索托马约尔','role'=>'法官','desc'=>'美国最高法院首位拉美裔法官'],
        ['name'=>'P.Diddy','role'=>'企业家','desc'=>'音乐与商业帝国缔造者'],
    ],
    'ESFJ'=>[
        ['name'=>'泰勒·斯威夫特','role'=>'歌手','desc'=>'全球流行天后，以真诚与粉丝建立连接'],
        ['name'=>'休·杰克曼','role'=>'演员','desc'=>'金刚狼扮演者，以温暖和敬业著称'],
        ['name'=>'詹妮弗·加纳','role'=>'演员','desc'=>'好莱坞女星，以亲和力著称'],
        ['name'=>'比尔·克林顿','role'=>'美国总统','desc'=>'美国第42任总统，极具个人魅力'],
        ['name'=>'史蒂夫·哈维','role'=>'主持人','desc'=>'脱口秀主持人，以幽默温暖著称'],
        ['name'=>'维纳斯·威廉姆斯','role'=>'网球运动员','desc'=>'网球传奇，体育界领军人物'],
    ],
    'ISTP'=>[
        ['name'=>'迈克尔·乔丹','role'=>'篮球运动员','desc'=>'篮球之神，NBA传奇巨星'],
        ['name'=>'布鲁斯·李','role'=>'武术家、演员','desc'=>'功夫电影巨星，截拳道创始人'],
        ['name'=>'克林特·伊斯特伍德','role'=>'演员、导演','desc'=>'好莱坞传奇，从演员到导演的完美转型'],
        ['name'=>'汤姆·克鲁斯','role'=>'演员','desc'=>'好莱坞顶级巨星，以特技表演著称'],
        ['name'=>'贝尔·格里尔斯','role'=>'探险家','desc'=>'"荒野求生"主持人，生存专家'],
        ['name'=>'克里斯蒂安·贝尔','role'=>'演员','desc'=>'蝙蝠侠扮演者，以角色投入著称'],
    ],
    'ISFP'=>[
        ['name'=>'迈克尔·杰克逊','role'=>'歌手','desc'=>'流行音乐之王，改变音乐和舞蹈的历史'],
        ['name'=>'鲍勃·迪伦','role'=>'音乐家','desc'=>'民谣摇滚传奇，诺贝尔文学奖获得者'],
        ['name'=>'大卫·鲍伊','role'=>'音乐家','desc'=>'摇滚变色龙，不断重塑音乐风格'],
        ['name'=>'拉娜·德雷','role'=>'歌手','desc'=>'以复古风格和诗意歌词著称'],
        ['name'=>'克劳德·莫奈','role'=>'画家','desc'=>'印象派创始人之一，光影大师'],
        ['name'=>'布兰妮·斯皮尔斯','role'=>'歌手','desc'=>'流行天后，90后一代音乐偶像'],
    ],
    'ESTP'=>[
        ['name'=>'麦当娜','role'=>'歌手','desc'=>'流行音乐女王，不断挑战界限'],
        ['name'=>'唐纳德·特朗普','role'=>'企业家、政治家','desc'=>'美国第45任总统，商业帝国缔造者'],
        ['name'=>'杰克·尼科尔森','role'=>'演员','desc'=>'好莱坞传奇，三次奥斯卡最佳男主角'],
        ['name'=>'欧内斯特·海明威','role'=>'作家','desc'=>'诺贝尔文学奖获得者，硬汉文学代表'],
        ['name'=>'尤塞恩·博尔特','role'=>'田径运动员','desc'=>'百米飞人，8枚奥运金牌得主'],
        ['name'=>'米基·洛克','role'=>'演员','desc'=>'好莱坞硬汉演员，以独特的表演风格著称'],
    ],
    'ESFP'=>[
        ['name'=>'玛丽莲·梦露','role'=>'演员','desc'=>'好莱坞传奇性感符号，流行文化偶像'],
        ['name'=>'埃尔顿·约翰','role'=>'音乐家','desc'=>'摇滚乐传奇，以华丽的舞台风格著称'],
        ['name'=>'亚当·桑德勒','role'=>'演员、喜剧家','desc'=>'好莱坞喜剧巨星，以幽默温暖著称'],
        ['name'=>'阿黛尔','role'=>'歌手','desc'=>'以深情的嗓音打动全球，格莱美赢家'],
        ['name'=>'杰米·奥利弗','role'=>'厨师','desc'=>'英国名厨，推动健康饮食革命'],
        ['name'=>'肯尼迪','role'=>'美国总统','desc'=>'美国第35任总统，以魅力和远见著称'],
    ],
];

// ============ 获取当前类型数据 ============
$mbtiType = $cert['mbti_type'];
$compat = $compatibility[$mbtiType] ?? ['best'=>['ENFP','ENTP'],'good'=>['INFJ','INTJ'],'grow'=>['ESFP','ESTP']];
$workStyle = $workStyles[$mbtiType] ?? ['style'=>'综合型工作者','env'=>'灵活多变的环境','strength'=>'多方面能力均衡'];
$learnPref = $learningPrefs[$mbtiType] ?? ['method'=>'多样化学习','prefer'=>'综合内容','tips'=>'保持好奇心'];
$teamRole = $teamRoles[$mbtiType] ?? ['role'=>'多面手','desc'=>'灵活适应各种角色','strengths'=>['适应力','学习能力','团队协作']];
$relation = $relationshipStyles[$mbtiType] ?? ['label'=>'综合型','desc'=>'在人际关系中保持平衡'];
$improve = $improvementTips[$mbtiType] ?? ['tips'=>['持续自我反思','保持开放心态','设定明确目标']];
$famous = $famousPeople[$mbtiType] ?? [['name'=>'未知','role'=>'','desc'=>'暂无数据']];

$strengthList = !empty($cert['strengths']) ? array_values(array_filter($cert['strengths'])) : ['思路稳定，能在熟悉场景里持续发挥'];
$weaknessList = !empty($cert['weaknesses']) ? array_values(array_filter($cert['weaknesses'])) : ['在高压或陌生环境下，容易出现自己的惯性盲点'];
$careerList = !empty($cert['careers']) ? array_values(array_filter($cert['careers'])) : ['适合能够发挥个人节奏与优势的岗位'];
$coreKeywords = array_slice($strengthList, 0, 3);
$createdAtLabel = !empty($cert['created_at']) ? date('Y年m月d日 H:i', strtotime($cert['created_at'])) : date('Y年m月d日 H:i');

$dimensionScores = [
    'EI' => [
        'key' => 'EI',
        'name' => $dimensionDetails['EI']['name'],
        'color' => $dimensionDetails['EI']['color'],
        'icon' => $dimensionDetails['EI']['icon'],
        'left_score' => (int)($scores['E'] ?? 0),
        'right_score' => (int)($scores['I'] ?? 0),
        'left_pct' => $eiPct,
        'right_pct' => 100 - $eiPct,
    ],
    'SN' => [
        'key' => 'SN',
        'name' => $dimensionDetails['SN']['name'],
        'color' => $dimensionDetails['SN']['color'],
        'icon' => $dimensionDetails['SN']['icon'],
        'left_score' => (int)($scores['S'] ?? 0),
        'right_score' => (int)($scores['N'] ?? 0),
        'left_pct' => $snPct,
        'right_pct' => 100 - $snPct,
    ],
    'TF' => [
        'key' => 'TF',
        'name' => $dimensionDetails['TF']['name'],
        'color' => $dimensionDetails['TF']['color'],
        'icon' => $dimensionDetails['TF']['icon'],
        'left_score' => (int)($scores['T'] ?? 0),
        'right_score' => (int)($scores['F'] ?? 0),
        'left_pct' => $tfPct,
        'right_pct' => 100 - $tfPct,
    ],
    'JP' => [
        'key' => 'JP',
        'name' => $dimensionDetails['JP']['name'],
        'color' => $dimensionDetails['JP']['color'],
        'icon' => $dimensionDetails['JP']['icon'],
        'left_score' => (int)($scores['J'] ?? 0),
        'right_score' => (int)($scores['P'] ?? 0),
        'left_pct' => $jpPct,
        'right_pct' => 100 - $jpPct,
    ],
];

$dominantDimensionKey = 'EI';
$winnerPctSum = 0;

foreach ($dimensionScores as $key => &$item) {
    $meta = $dimensionDetails[$key];
    $leftMeta = $meta['left'];
    $rightMeta = $meta['right'];
    $item['winner'] = $item['left_score'] >= $item['right_score'] ? $leftMeta['letter'] : $rightMeta['letter'];
    $item['loser'] = $item['winner'] === $leftMeta['letter'] ? $rightMeta['letter'] : $leftMeta['letter'];
    $item['winner_meta'] = $item['winner'] === $leftMeta['letter'] ? $leftMeta : $rightMeta;
    $item['loser_meta'] = $item['winner'] === $leftMeta['letter'] ? $rightMeta : $leftMeta;
    $item['winner_score'] = max($item['left_score'], $item['right_score']);
    $item['loser_score'] = min($item['left_score'], $item['right_score']);
    $item['winner_pct'] = $item['winner'] === $leftMeta['letter'] ? $item['left_pct'] : $item['right_pct'];
    $item['loser_pct'] = 100 - $item['winner_pct'];
    $item['gap'] = abs($item['left_score'] - $item['right_score']);

    if ($item['gap'] >= 12) {
        $item['intensity'] = '非常明显';
    } elseif ($item['gap'] >= 7) {
        $item['intensity'] = '比较明显';
    } elseif ($item['gap'] >= 3) {
        $item['intensity'] = '轻度偏向';
    } else {
        $item['intensity'] = '相对均衡';
    }

    $item['summary'] = '你在' . $meta['name'] . '上更靠近 ' . $item['winner_meta']['label'] . '，通常会表现出“' . $item['winner_meta']['desc'] . '”这一面。';
    $winnerPctSum += $item['winner_pct'];

    if ($item['gap'] > $dimensionScores[$dominantDimensionKey]['gap']) {
        $dominantDimensionKey = $key;
    }
}
unset($item);

$dominantDimension = $dimensionScores[$dominantDimensionKey];
$clarityScore = (int)round($winnerPctSum / count($dimensionScores));
if ($clarityScore >= 70) {
    $clarityLabel = '偏好非常清晰';
} elseif ($clarityScore >= 60) {
    $clarityLabel = '偏好较清晰';
} else {
    $clarityLabel = '偏好比较均衡';
}

$nextActionList = [
    '优先选择“' . $workStyle['env'] . '”这类环境，更容易进入高表现状态。',
    '学习时尽量采用“' . $learnPref['method'] . '”的方式，你会更容易吸收和坚持。',
    '团队里可以主动承担“' . $teamRole['role'] . '”相关职责，更容易把天然优势发挥出来。',
];

$summaryParagraph = '你的人格结果整体呈现出“' . $clarityLabel . '”的状态，其中最突出的维度是' . $dominantDimension['name'] . '：你明显更偏向 ' . $dominantDimension['winner_meta']['label'] . '，这意味着你在表达、获取信息、做决定与安排节奏时，会更自然地采用这一侧的方式。';

$insightMetrics = [
    ['label' => '人格清晰度', 'value' => $clarityLabel, 'note' => '四个维度平均偏好约 ' . $clarityScore . '%'],
    ['label' => '最突出维度', 'value' => $dominantDimension['name'], 'note' => $dominantDimension['winner_meta']['label'] . ' 领先 ' . $dominantDimension['gap'] . ' 分'],
    ['label' => '高表现环境', 'value' => $workStyle['env'], 'note' => '更容易进入心流和高效率状态'],
    ['label' => '结果生成时间', 'value' => $createdAtLabel, 'note' => '方便你后续对比不同阶段的变化'],
];

$compatSections = [
    ['class' => 'best', 'icon' => 'bi-stars', 'title' => '高契合搭档', 'desc' => '通常更容易在交流节奏和理解方式上同频。', 'types' => $compat['best']],
    ['class' => 'good', 'icon' => 'bi-heart', 'title' => '舒服型关系', 'desc' => '不一定完全相同，但合作和相处往往很顺。', 'types' => $compat['good']],
    ['class' => 'grow', 'icon' => 'bi-lightning-charge', 'title' => '互补成长型', 'desc' => '差异感会更强，但也最容易带来新的视角。', 'types' => $compat['grow']],
];

$extendedTips = array_slice(array_values(array_unique(array_merge($nextActionList, $improve['tips']))), 0, 6);
$dimensionRanking = array_values($dimensionScores);
usort($dimensionRanking, static function ($a, $b) {
    return $b['gap'] <=> $a['gap'];
});
?>




<style>
/* ======== 结果页专属动画 ======== */
@keyframes heroGlow {
    0%, 100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.15); opacity: 1; }
}
@keyframes floatIcon {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    25% { transform: translateY(-8px) rotate(3deg); }
    75% { transform: translateY(4px) rotate(-2deg); }
}
@keyframes shimmer {
    0% { background-position: -200% center; }
    100% { background-position: 200% center; }
}
@keyframes certFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
}
@keyframes barGrow {
    from { width: 0%; }
}
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes particleFloat {
    0%, 100% { transform: translateY(0) translateX(0); opacity: 0.4; }
    25% { transform: translateY(-20px) translateX(10px); opacity: 0.8; }
    50% { transform: translateY(-10px) translateX(-5px); opacity: 0.6; }
    75% { transform: translateY(-30px) translateX(15px); opacity: 0.3; }
}

/* Hero 动态光晕 */
.hero-glow-orb {
    animation: heroGlow 6s ease-in-out infinite;
    will-change: transform, opacity;
}
.hero-glow-orb:nth-child(2) { animation-delay: -2s; animation-duration: 8s; }
.hero-glow-orb:nth-child(3) { animation-delay: -4s; animation-duration: 7s; }

/* 类型图标浮动 */
.type-icon-float {
    animation: floatIcon 4s ease-in-out infinite;
}

/* MBTI 类型文字闪光 */
.type-shimmer {
    background: linear-gradient(90deg, <?= $color ?> 0%, <?= $color ?> 40%, rgba(255,255,255,0.7) 50%, <?= $color ?> 60%, <?= $color ?> 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 4s linear infinite;
}

/* 证书浮动（保留兼容） */
.cert-float {
    animation: certFloat 5s ease-in-out infinite;
}

/* ======== 证书 v3 样式 ======== */
@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(52,211,153,0.4); }
    50% { opacity: 0.6; box-shadow: 0 0 0 4px rgba(52,211,153,0); }
}

/* 响应式布局 */
@media (min-width: 768px) {
    .cert-main-row { flex-direction: row !important; }
    .cert-type-col { border-left: 1px solid var(--surface-result-outline-soft); border-right: 1px solid var(--surface-result-outline-soft); padding-left: 24px !important; padding-right: 24px !important; }
    .cert-qr-col { border-left: none; }
}


@media (max-width: 767px) {
    #certificateCard { margin: 0 -8px; border-radius: 16px; }
    .cert-type-col { flex-direction: row; gap: 20px; text-align: left; padding: 16px !important; }
    .cert-type-col > div[style*="110px"] { width: 80px; height: 80px; flex-shrink: 0; }
    .cert-type-col > div[style*="110px"] span { font-size: 2rem; }
}

/* 二维码悬停 */
#qrWrapper:hover { transform: scale(1.06) rotate(-1deg); }

/* 进度条生长 */
.bar-animate {
    animation: barGrow 1.2s cubic-bezier(.4,0,.2,1) forwards;
}

/* 段落淡入 */
.result-fade-in {
    opacity: 0;
    animation: fadeSlideUp 0.6s ease forwards;
}

/* 粒子 */
.result-particle {
    position: absolute;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    pointer-events: none;
    animation: particleFloat 8s ease-in-out infinite;
    opacity: 0.6;
}

/* 维度分析卡片进度条 */
.dimension-card .progress {
    background: var(--progress-bg) !important;
}

/* 操作按钮深色适配 */
.action-btn-outline {
    background: var(--bg-card) !important;
    border-color: var(--border-3) !important;
    color: var(--text-2) !important;
}
.action-btn-outline:hover {
    border-color: var(--primary) !important;
    color: var(--primary) !important;
}

/* ======== 新增模块样式 ======== */

/* 雷达图容器 */
.radar-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem;
}
.radar-container canvas {
    max-width: 320px;
    max-height: 320px;
}
.dimension-sidebar-card {
    height: 100%;
}
.dimension-side-stack {
    display: grid;
    gap: 14px;
    margin-top: 1.1rem;
}
.dimension-note-card {
    padding: 16px;
    border-radius: 18px;
    background: var(--surface-result-subtle-2);
    border: 1px solid var(--surface-result-outline-soft);
}
.dimension-note-title {
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--text-4);
    margin-bottom: 12px;
}
.dimension-note-text {
    color: var(--text-3);
    line-height: 1.8;
    font-size: 0.84rem;
}
.dimension-highlight-item {
    padding: 14px 14px 12px;
    border-radius: 16px;
    background: var(--surface-result-subtle-2);
    border: 1px solid var(--surface-result-outline-soft);
}
.dimension-highlight-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 10px;
}
.dimension-rank-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 999px;
    background: var(--surface-result-rank);
    color: var(--text-1);
    font-size: 0.78rem;
    font-weight: 700;
    flex-shrink: 0;
}
.dimension-highlight-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 0.74rem;
    font-weight: 700;
    line-height: 1;
    text-align: center;
}
.dimension-highlight-desc {
    color: var(--text-3);
    font-size: 0.82rem;
    line-height: 1.75;
}
.mini-progress {
    height: 6px;
    border-radius: 999px;
    background: var(--surface-result-rank);
    overflow: hidden;
}
.mini-progress > span {
    display: block;
    height: 100%;
    border-radius: inherit;
}

#heroPill {
    background: var(--surface-floating-pill) !important;
}
#certNoPill {
    background: var(--surface-floating-pill-soft) !important;
}
.certificate-shell {
    background: var(--surface-certificate-shell) !important;
    transition: background 0.35s ease, box-shadow 0.35s ease;
}
.certificate-frame {
    background: var(--surface-certificate-frame) !important;
}
.certificate-inner {
    background: var(--surface-certificate-inner) !important;
}
.certificate-meta-box,
.certificate-ghost-btn,
.dimension-subpanel {
    background: var(--surface-certificate-soft) !important;
    border: 1px solid var(--surface-result-outline-soft) !important;
}
.certificate-ghost-btn {
    color: var(--text-2) !important;
}
.certificate-divider {
    background: linear-gradient(90deg, transparent, var(--surface-result-divider) 20%, <?= $color ?>30 50%, var(--surface-result-divider) 80%, transparent) !important;
}
.certificate-type-core {
    background: linear-gradient(180deg, <?= $color ?>15, rgba(20,20,36,0.98)) !important;
}
.certificate-dim-track {
    background: var(--surface-result-rank) !important;
}
.result-soft-panel {
    background: var(--surface-result-subtle-2) !important;
    border: 1px solid var(--surface-result-outline-soft) !important;
}
.result-soft-pill {
    background: var(--surface-result-subtle) !important;
    border: 1px solid var(--surface-result-outline) !important;
    color: var(--text-2) !important;
}

:root[data-theme="light"] .detail-panel {

    box-shadow: 0 22px 54px rgba(15,23,42,0.08);
}
:root[data-theme="light"] .dim-detail-card:hover,
:root[data-theme="light"] .compat-card:hover,
:root[data-theme="light"] .report-card:hover,
:root[data-theme="light"] .famous-card:hover {
    box-shadow: 0 18px 42px rgba(109,106,248,0.14);
}
:root[data-theme="light"] .certificate-shell {
    box-shadow:
        0 0 0 1px rgba(109,106,248,0.12),
        0 8px 18px rgba(15,23,42,0.06),
        0 24px 56px rgba(109,106,248,0.12),
        0 40px 90px rgba(15,23,42,0.08) !important;
}
:root[data-theme="light"] .cert-type-col {
    border-left-color: rgba(109,106,248,0.12) !important;
    border-right-color: rgba(109,106,248,0.12) !important;
}
:root[data-theme="light"] .certificate-type-core {
    background: linear-gradient(180deg, <?= $color ?>20, rgba(255,255,255,0.96)) !important;
    box-shadow: inset 0 0 0 1px <?= $color ?>12;
}


/* 维度详解卡片 */

.dim-detail-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.dim-detail-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.3);
}

/* 兼容性标签 */
.compat-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}
.compat-badge.best {
    background: rgba(52,211,153,0.1);
    color: #34D399;
    border: 1px solid rgba(52,211,153,0.2);
}
.compat-badge.good {
    background: rgba(129,140,248,0.1);
    color: #818CF8;
    border: 1px solid rgba(129,140,248,0.2);
}
.compat-badge.grow {
    background: rgba(251,191,36,0.1);
    color: #FBBF24;
    border: 1px solid rgba(251,191,36,0.2);
}

/* 兼容性卡片 */
.compat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: default;
}
.compat-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}

/* 人格报告卡片 */
.report-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}
.report-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.3);
}

/* 名人卡片 */
.famous-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    min-width: 180px;
    flex-shrink: 0;
}
.famous-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.4);
}

/* 名人墙横向滚动 */
.famous-scroll {
    display: flex;
    gap: 1rem;
    overflow-x: auto;
    padding-bottom: 0.75rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}
.famous-scroll::-webkit-scrollbar {
    height: 4px;
}
.famous-scroll::-webkit-scrollbar-track {
    background: var(--progress-bg);
    border-radius: 2px;
}
.famous-scroll::-webkit-scrollbar-thumb {
    background: var(--border-3);
    border-radius: 2px;
}
.famous-scroll > * {
    scroll-snap-align: start;
}

/* 报告子项标题图标 */
.report-icon-box {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* 改善建议条目 */
.tip-item {
    display: flex;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    background: var(--bg-2);
    margin-bottom: 0.5rem;
    transition: background 0.2s ease;
    font-size: 0.88rem;
    color: var(--text-2);
    line-height: 1.6;
}
.tip-item:hover {
    background: var(--bg-3);
}

/* Section 标题装饰 */
.section-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 0.5rem;
}
.section-title-line {
    width: 40px;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border-3), transparent);
}

.detail-panel {
    background: var(--surface-result-panel);
    border: 1px solid var(--border-2);
    border-radius: 24px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.18);
    transition: background 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
}
.metric-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    background: var(--surface-result-subtle);
    border: 1px solid var(--surface-result-outline);
    color: var(--text-2);
    font-size: 0.8rem;
}
.keyword-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 12px;
    font-size: 0.82rem;
    font-weight: 600;
    background: var(--surface-result-subtle);
    border: 1px solid var(--surface-result-outline);
    color: var(--text-2);
}
.insight-item {
    height: 100%;
    padding: 18px;
    border-radius: 18px;
    background: linear-gradient(180deg, var(--surface-result-subtle), transparent);
    border: 1px solid var(--surface-result-outline-soft);
}
.insight-item-label {
    font-size: 0.74rem;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: var(--text-4);
}
.insight-item-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-1);
    line-height: 1.5;
}
.insight-item-note {
    font-size: 0.82rem;
    color: var(--text-3);
    line-height: 1.7;
}
.detail-list {
    display: grid;
    gap: 12px;
}
.detail-list-item {
    display: flex;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 16px;
    background: var(--surface-result-subtle-2);
    border: 1px solid var(--surface-result-outline-soft);
}
.detail-list-item i {
    color: var(--primary);
    font-size: 0.95rem;
    margin-top: 3px;
}
.dimension-meter {
    height: 8px;
    border-radius: 999px;
    background: var(--progress-bg);
    overflow: hidden;
}
.dimension-meter > span {
    display: block;
    height: 100%;
    border-radius: inherit;
}
.action-plan-item {
    display: flex;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 14px;
    background: var(--surface-result-subtle-2);
    border: 1px solid var(--surface-result-outline-soft);
}
.action-plan-index {
    width: 28px;
    height: 28px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}
.compat-type-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 64px;
    padding: 10px 14px;
    border-radius: 14px;
    background: var(--surface-result-subtle);
    border: 1px solid var(--surface-result-outline-soft);
    font-weight: 700;
    color: var(--text-1);
    letter-spacing: 1px;
}


@media (max-width: 767px) {
    .famous-scroll {
        gap: 0.75rem;
    }
    .famous-card {
        min-width: 160px;
    }
    .insight-item,
    .detail-list-item,
    .action-plan-item {
        padding: 14px;
    }
}
</style>


<!-- ==================== 结果头部 ==================== -->
<section class="py-5 py-lg-6 result-hero-section" style="background:linear-gradient(135deg, <?= $color ?>18 0%, <?= $color ?>08 50%, var(--bg-0) 100%);position:relative;overflow:hidden;">
    <!-- 动态背景光晕 -->
    <div style="position:absolute;inset:0;pointer-events:none;">
        <div class="hero-glow-orb" style="position:absolute;top:10%;left:10%;width:200px;height:200px;background:radial-gradient(circle,<?= $color ?>20,transparent);border-radius:50%;"></div>
        <div class="hero-glow-orb" style="position:absolute;bottom:10%;right:10%;width:300px;height:300px;background:radial-gradient(circle,<?= $color ?>15,transparent);border-radius:50%;"></div>
        <div class="hero-glow-orb" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:250px;height:250px;background:radial-gradient(circle,<?= $color ?>10,transparent);border-radius:50%;"></div>
        <!-- 浮动粒子 -->
        <div class="result-particle" style="top:20%;left:15%;background:<?= $color ?>;animation-delay:-1s;"></div>
        <div class="result-particle" style="top:30%;right:20%;background:<?= $color ?>;animation-delay:-3s;width:4px;height:4px;"></div>
        <div class="result-particle" style="top:60%;left:25%;background:<?= $color ?>;animation-delay:-5s;width:8px;height:8px;"></div>
        <div class="result-particle" style="top:15%;right:30%;background:<?= $color ?>;animation-delay:-2s;width:5px;height:5px;"></div>
        <div class="result-particle" style="bottom:30%;left:40%;background:<?= $color ?>;animation-delay:-7s;"></div>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center animate-fadeInUp">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-4" style="background:rgba(18,18,30,0.6);backdrop-filter:blur(8px);border:1px solid var(--border-2);transition:all 0.5s;" id="heroPill">
                    <i class="bi bi-patch-check-fill" style="color:<?= $color ?>;"></i>
                    <span style="font-size:0.85rem;color:var(--text-2);">MBTI 性格类型测试结果</span>
                </div>
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center type-icon-float" style="width:96px;height:96px;border-radius:28px;background:linear-gradient(135deg,<?= $color ?>25,<?= $color ?>10);box-shadow:0 8px 32px <?= $color ?>30;">
                        <span style="font-size:2.8rem;"><?= $icon ?></span>
                    </div>
                </div>
                <h1 class="fw-black mb-2 type-shimmer" style="font-size:clamp(2.8rem, 7vw, 4.2rem);letter-spacing:4px;">
                    <?= $cert['mbti_type'] ?>
                </h1>
                <h2 class="fw-bold mb-3" style="font-size:clamp(1.1rem, 2.5vw, 1.5rem);color:var(--text-1);">
                    <?= $cert['type_name'] ?> · <?= $cert['type_nickname'] ?>
                </h2>
                <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill" style="background:rgba(18,18,30,0.5);backdrop-filter:blur(8px);border:1px solid var(--border-1);transition:all 0.5s;" id="certNoPill">
                    <i class="bi bi-hash" style="color:<?= $color ?>;font-size:0.8rem;"></i>
                    <span style="font-size:0.88rem;color:var(--text-2);font-weight:500;letter-spacing:0.5px;"><?= $cert['certificate_no'] ?></span>
                </div>
            </div>
        </div>
    </div>
    <!-- 底部波浪 -->
    <div style="position:absolute;bottom:0;left:0;right:0;">
        <svg viewBox="0 0 1440 60" class="result-hero-wave" style="display:block;width:100%;">
            <path d="M0,30 C480,60 960,0 1440,30 L1440,60 L0,60 Z" fill="var(--bg-0)"/>
        </svg>
    </div>
</section>

<!-- ==================== 证书区域（精致重设计 v3） ==================== -->
<section id="certificateSection" style="
    padding: 64px 0 72px;
    background: var(--bg-0);
    position: relative;
    overflow: hidden;
">
    <!-- 背景装饰：微妙的网格 + 光晕 -->
    <div style="
        position: absolute; inset: 0;
        background-image:
            radial-gradient(circle at 1px 1px, rgba(129,140,248,0.03) 1px, transparent 0);
        background-size: 32px 32px;
        pointer-events: none;
    "></div>
    <div style="
        position: absolute; top: -120px; left: 50%;
        transform: translateX(-50%);
        width: 800px; height: 500px;
        background: radial-gradient(ellipse, <?= $color ?>07, transparent 70%);
        pointer-events: none;
    "></div>

    <div class="container position-relative" style="z-index: 1;">
        <!-- 标题 -->
        <div class="text-center mb-5 animate-on-scroll">
            <div class="d-inline-flex align-items-center gap-2 px-4 py-1.5 rounded-full mb-3"
                 style="background: linear-gradient(135deg, <?= $color ?>10, <?= $color ?>05); border: 1px solid <?= $color ?>18;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="<?= $color ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span style="font-size: 0.82rem; font-weight: 650; color: <?= $color ?>; letter-spacing: 0.5px;">PERSONALITY CERTIFICATE</span>
            </div>
            <h4 class="fw-bold" style="letter-spacing: 1px;">你的专属 MBTI 鉴定证书</h4>
            <p style="color: var(--text-4); font-size: 0.85rem; margin-top: 6px;">扫描二维码分享你的性格类型，或下载保存为图片</p>
        </div>

        <!-- ============ 证书卡片 ============ -->
        <div class="animate-on-scroll" style="max-width: 900px; margin: 0 auto;">
            <div id="certificateCard" class="certificate-shell" style="

                /* 外层框架 */
                border-radius: 20px;
                overflow: hidden;
                position: relative;
                background: linear-gradient(165deg, #10101c 0%, #181830 40%, #12121f 100%);
                box-shadow:
                    0 0 0 1px rgba(255,255,255,0.06),
                    0 2px 4px rgba(0,0,0,0.2),
                    0 16px 48px rgba(0,0,0,0.4),
                    0 32px 80px rgba(0,0,0,0.25);
            ">

                <!-- ══ 顶部品牌条 ══ -->
                <div style="
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 20px 28px 0;
                    position: relative;
                ">
                    <div class="d-flex align-items-center gap-2.5">
                        <div style="
                            width: 38px; height: 38px; border-radius: 10px;
                            background: linear-gradient(135deg, <?= $color ?>30, <?= $color ?>15);
                            display: flex; align-items: center; justify-content: center;
                        "><i class="bi bi-puzzle-fill" style="color: <?= $color ?>; font-size: 1rem;"></i></div>
                        <div>
                            <div style="font-size: 0.82rem; font-weight: 750; letter-spacing: 2.5px; color: var(--text-1); line-height: 1;">MBTI TEST</div>
                            <div style="font-size: 0.65rem; color: var(--text-4); letter-spacing: 0.8px;">Myers-Briggs Type Indicator</div>
                        </div>
                    </div>
                    <!-- 右上角认证标记 -->
                    <div class="d-none d-md-flex align-items-center gap-1.5 px-3 py-1.5 rounded-full"
                         style="background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.2);">
                        <div style="width: 6px; height: 6px; border-radius: 50%; background: #34D399; animation: pulse-dot 2s ease-in-out infinite;"></div>
                        <span style="font-size: 0.68rem; font-weight: 700; color: #34D399; letter-spacing: 1px;">VERIFIED</span>
                    </div>
                </div>

                <!-- ══ 内部内容区（带装饰边框）══ -->
                <div class="certificate-frame" style="
                    margin: 20px 24px 28px;

                    border-radius: 16px;
                    position: relative;
                    /* 双层边框效果 */
                    padding: 3px;
                    background: linear-gradient(
                        135deg,
                        rgba(255,255,255,0.08) 0%,
                        rgba(255,255,255,0.02) 30%,
                        rgba(<?= substr($color,1,2) ?>,<?= substr($color,3,2) ?>,<?= substr($color,5,2) ?>,0.06) 50%,
                        rgba(255,255,255,0.02) 70%,
                        rgba(255,255,255,0.06) 100%
                    );
                ">
                    <div class="certificate-inner" style="
                        border-radius: 14px;

                        background: linear-gradient(170deg, rgba(20,20,36,0.95) 0%, rgba(14,14,26,1) 100%);
                        padding: 36px 32px 28px;
                    ">
                        <!-- 四角装饰点 -->
                        <?php foreach([[8,8,'tl'],[null,8,'tr'],[8,null,'bl'],[null,null,'br']] as list($t,$l,$pos)):
                            $topStyle = $t !== null ? 'top:'.$t.'px;' : 'bottom:8px;';
                            $leftStyle = $l !== null ? 'left:'.$l.'px;' : 'right:8px;';
                        ?>
                        <div style="position:absolute;<?= $topStyle ?><?= $leftStyle ?>width:10px;height:10px;border-radius:50%;background:<?= $color ?>40;box-shadow:0 0 6px <?= $color ?>30;"></div>
                        <?php endforeach; ?>

                        <!-- ──── 上半部分：对称双栏 ──── -->
                        <div style="display: flex; flex-direction: column; gap: 0;" class="cert-inner-layout">

                            <!-- 左右主内容区 -->
                            <div style="display: flex; flex-direction: column; gap: 28px;" class="cert-main-row">
                                <!-- === 左侧：文字信息 === -->
                                <div style="flex: 1; min-width: 0;">
                                    <!-- 证书抬头 -->
                                    <div class="text-center text-md-start mb-4">
                                        <div style="
                                            font-size: 0.65rem;
                                            letter-spacing: 5px;
                                            opacity: 0.3;
                                            text-transform: uppercase;
                                            font-weight: 400;
                                        ">Certificate of Personality Type</div>
                                        <h3 style="
                                            font-size: 1.5rem;
                                            letter-spacing: 3px;
                                            margin: 6px 0 0;
                                            font-weight: 800;
                                            background: linear-gradient(90deg, var(--text-1), <?= $color ?>cc, var(--text-1));
                                            -webkit-background-clip: text;
                                            -webkit-text-fill-color: transparent;
                                        ">性格类型鉴定书</h3>
                                    </div>

                                    <!-- 证书说明 -->
                                    <div class="mb-4" style="
                                        padding: 10px 14px;
                                        background: rgba(255,255,255,0.02);
                                        border-radius: 10px;
                                        border-left: 3px solid <?= $color ?>;
                                        display: flex;
                                        align-items: center;
                                        gap: 10px;
                                    ">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" style="flex-shrink:0;opacity:0.7;"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="<?= $color ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span style="font-size: 0.73rem; color: var(--text-3); letter-spacing: 0.3px;">已完成 MBTI 人格类型评估，结果如下</span>
                                    </div>



                                    <!-- 维度数据条 -->
                                    <div style="display: flex; flex-direction: column; gap: 10px;" class="cert-bars">
                                        <?php
                                        $dimData = [
                                            ['label'=>'能量方向','key'=>'EI','pct'=>$eiPct,'win'=>$eiLeft,'c'=>'#818CF8','full'=>'Extraversion vs Introversion'],
                                            ['label'=>'信息获取','key'=>'SN','pct'=>$snPct,'win'=>$snLeft,'c'=>'#22D3EE','full'=>'Sensing vs Intuition'],
                                            ['label'=>'决策方式','key'=>'TF','pct'=>$tfPct,'win'=>$tfLeft,'c'=>'#F472B6','full'=>'Thinking vs Feeling'],
                                            ['label'=>'生活方式','key'=>'JP','pct'=>$jpPct,'win'=>$jpLeft,'c'=>'#FBBF24','full'=>'Judging vs Perceiving'],
                                        ];
                                        foreach ($dimData as $di):
                                            $opposite = str_replace(['E','I','S','N','T','F','J','P'],['I','E','N','S','F','T','P','J'], $di['win']);
                                        ?>
                                        <div style="display: grid; grid-template-columns: 56px 1fr auto auto; align-items: center; gap: 10px;">
                                            <div style="font-size: 0.68rem; color: var(--text-4); font-weight: 550; white-space: nowrap;"><?= $di['label'] ?></div>
                                            <div class="certificate-dim-track" style="height: 6px; border-radius: 3px; background: rgba(255,255,255,0.05); overflow: hidden; position: relative;">
                                                <div style="
                                                    position: absolute; left: 0; top: 0; height: 100%;
                                                    width: <?= $di['pct'] ?>%;
                                                    border-radius: 3px;
                                                    background: linear-gradient(90deg, <?= $di['c'] ?>60, <?= $di['c'] ?>);
                                                    box-shadow: 0 0 8px <?= $di['c'] ?>30;
                                                "></div>
                                            </div>
                                            <span class="fw-bold" style="font-size: 0.76rem; color: <?= $di['c'] ?>; min-width: 42px; text-align: right;"><?= $di['win'] ?> <?= $di['pct'] ?>%</span>
                                            <span style="font-size: 0.63rem; color: var(--text-4); min-width: 38px; text-align: right;"><?= $opposite ?> <?= 100-$di['pct'] ?>%</span>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- === 中间：类型大展示 === -->
                                <div style="
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    min-width: 220px;
                                    padding: 20px 16px;
                                " class="cert-type-col">
                                    <!-- 圆形图标背景 -->
                                    <div style="
                                        width: 110px; height: 110px; border-radius: 50%;
                                        display: flex; align-items: center; justify-content: center;
                                        position: relative;
                                        margin-bottom: 16px;
                                    ">
                                        <!-- 外圈光环动画 -->
                                        <div style="
                                            position: absolute; inset: -4px;
                                            border-radius: 50%;
                                            border: 2px dashed <?= $color ?>30;
                                            animation: spin-slow 20s linear infinite;
                                        "></div>
                                        <!-- 内圈渐变 -->
                                        <div style="
                                            position: absolute; inset: 0;
                                            border-radius: 50%;
                                            background: conic-gradient(from 0deg, <?= $color ?>20, transparent, <?= $color ?>15, transparent, <?= $color ?>20);
                                            animation: spin-slow 15s linear infinite reverse;
                                        "></div>
                                        <!-- 实体底色 -->
                                        <div class="certificate-type-core" style="
                                            position: absolute; inset: 4px;
                                            border-radius: 50%;
                                            background: linear-gradient(180deg, <?= $color ?>15, rgba(20,20,36,0.98));
                                            z-index: 1;
                                        "></div>

                                        <!-- 图标 -->
                                        <span style="
                                            font-size: 2.8rem;
                                            position: relative; z-index: 2;
                                            filter: drop-shadow(0 4px 12px <?= $color ?>50);
                                        "><?= $icon ?></span>
                                    </div>

                                    <!-- 类型字母 -->
                                    <div class="fw-black" style="
                                        font-size: 2.8rem;
                                        color: <?= $color ?>;
                                        letter-spacing: 6px;
                                        line-height: 1;
                                        text-shadow: 0 0 40px <?= $color ?>35, 0 2px 4px rgba(0,0,0,0.3);
                                    "><?= $cert['mbti_type'] ?></div>
                                    <!-- 名称 -->
                                    <div class="fw-semibold" style="
                                        font-size: 1.05rem;
                                        color: var(--text-1);
                                        margin-top: 4px;
                                        letter-spacing: 1px;
                                    "><?= $cert['type_name'] ?></div>
                                    <div style="
                                        font-size: 0.75rem;
                                        color: var(--text-4);
                                        margin-top: 2px;
                                    ?>">「 <?= htmlspecialchars($cert['type_nickname']) ?> 」</div>
                                </div>

                                <!-- === 右侧：二维码 + 信息 === -->
                                <div style="
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    min-width: 200px;
                                    padding: 16px;
                                    gap: 14px;
                                " class="cert-qr-col">
                                    <!-- 二维码 -->
                                    <div id="qrWrapper" style="
                                        background: #fff;
                                        border-radius: 14px;
                                        padding: 12px;
                                        box-shadow:
                                            0 4px 16px rgba(0,0,0,0.3),
                                            0 0 0 1px rgba(255,255,255,0.1);
                                        transition: transform 0.4s cubic-bezier(.16,1,.3,1);
                                    ">
                                        <div id="qrcodeCanvas" style="width: 130px; height: 130px; display: flex; align-items: center; justify-content: center;">
                                            <div style="color:#333;font-size:0.65rem;text-align:center;opacity:0.4;">
                                                <i class="bi bi-qrcode d-block" style="font-size:1.6rem;margin-bottom:2px;"></i>
                                                加载中...
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 扫码提示 -->
                                    <div style="text-align: center;">
                                        <div style="font-size: 0.74rem; font-weight: 600; color: var(--text-2);">扫码查看详情</div>
                                        <div style="font-size: 0.63rem; color: var(--text-4); margin-top: 1px;">手机扫描二维码</div>
                                    </div>

                                    <!-- 编号和日期 -->
                                    <div class="certificate-meta-box" style="
                                        width: 100%;
                                        display: flex;

                                        flex-direction: column;
                                        gap: 8px;
                                        padding: 12px 14px;
                                        background: rgba(255,255,255,0.02);
                                        border-radius: 10px;
                                        border: 1px solid rgba(255,255,255,0.04);
                                    ">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 0.6rem; opacity: 0.3; letter-spacing: 1px; text-transform: uppercase;">No.</span>
                                            <span style="font-size: 0.7rem; font-weight: 600; color: var(--text-2); font-family: monospace; letter-spacing: 0.5px;"><?= $cert['certificate_no'] ?></span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 0.6rem; opacity: 0.3; letter-spacing: 1px; text-transform: uppercase;">Date</span>
                                            <span style="font-size: 0.7rem; font-weight: 500; color: var(--text-2);"><?= date('Y.m.d', strtotime($cert['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ──── 分割线 ──── -->
                            <div class="certificate-divider" style="
                                height: 1px;
                                margin: 24px 0 0;

                                background: linear-gradient(
                                    90deg,
                                    transparent,
                                    rgba(255,255,255,0.08) 20%,
                                    <?= $color ?>30 50%,
                                    rgba(255,255,255,0.08) 80%,
                                    transparent
                                );
                            "></div>

                            <!-- ──── 底部操作栏 ──── -->
                            <div style="
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 10px;
                                padding: 20px 0 4px;
                                flex-wrap: wrap;
                            ">
                                <button onclick="downloadCertificate()" style="
                                    display: inline-flex; align-items: center; gap: 6px;
                                    padding: 10px 22px;
                                    border-radius: 10px;
                                    font-size: 0.82rem; font-weight: 650;
                                    color: #fff; border: none; cursor: pointer;
                                    background: linear-gradient(135deg, var(--primary), #A78BFA);
                                    box-shadow: 0 3px 12px var(--primary-glow);
                                    transition: all 0.3s ease;
                                " onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px var(--primary-glow)'"
                                   onmouseout="this.style.transform='';this.style.boxShadow='0 3px 12px var(--primary-glow)'">
                                    <i class="bi bi-download"></i> 保存图片
                                </button>
                                <button onclick="copyLink()" class="certificate-ghost-btn" style="

                                    display: inline-flex; align-items: center; gap: 6px;
                                    padding: 10px 22px;
                                    border-radius: 10px;
                                    font-size: 0.82rem; font-weight: 600;
                                    background: rgba(255,255,255,0.05);
                                    color: var(--text-2);
                                    border: 1px solid rgba(255,255,255,0.08);
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                " onmouseover="this.style.transform='translateY(-2px)';this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
                                   onmouseout="this.style.transform='';this.style.borderColor='rgba(255,255,255,0.08)';this.style.color='var(--text-2)'">
                                    <i class="bi bi-link-45deg"></i> 复制链接
                                </button>
                                <a href="test.php" class="certificate-ghost-btn" style="

                                    display: inline-flex; align-items: center; gap: 6px;
                                    padding: 10px 22px;
                                    border-radius: 10px;
                                    font-size: 0.82rem; font-weight: 600;
                                    background: rgba(255,255,255,0.05);
                                    color: var(--text-2);
                                    border: 1px solid rgba(255,255,255,0.08);
                                    text-decoration: none;
                                    cursor: pointer;
                                    transition: all 0.3s ease;
                                " onmouseover="this.style.transform='translateY(-2px)';this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
                                   onmouseout="this.style.transform='';this.style.borderColor='rgba(255,255,255,0.08)';this.style.color='var(--text-2)'">
                                    <i class="bi bi-arrow-repeat"></i> 再测一次
                                </a>
                            </div>

                        </div><!-- end inner content -->
                    </div><!-- end inner bg -->
                </div><!-- end gradient border -->

                <!-- 底部装饰线 -->
                <div style="height: 4px; background: linear-gradient(90deg, transparent, <?= $color ?>40, <?= $color ?>aa, <?= $color ?>40, transparent);"></div>
            </div><!-- end certificateCard -->
        </div><!-- end wrapper -->

        <!-- 底部说明 -->
        <div class="text-center mt-4 animate-on-scroll" style="font-size: 0.75rem; color: var(--text-4);">
            <i class="bi bi-info-circle me-1" style="font-size: 0.7rem;"></i>
            本证书仅供娱乐与自我探索参考，不作为专业心理学评估依据
        </div>
    </div>
</section>

<!-- ==================== 结果总览 ==================== -->
<section class="pb-5">
    <div class="container">
        <div class="section-title mb-2">
            <span class="section-title-line"></span>
            <span class="fw-bold" style="letter-spacing:1px;">结果总览</span>
            <span class="section-title-line"></span>
        </div>
        <p class="text-center mb-4" style="color:var(--text-3);max-width:720px;margin-inline:auto;">不是只给你一个四字母结论，这一版会把你的偏好结构、优势落点和实际应用场景一起摊开讲清楚。</p>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-7">
                <div class="detail-panel p-4 p-lg-5 h-100">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="metric-pill"><i class="bi bi-stars" style="color:<?= $color ?>;"></i><?= htmlspecialchars($cert['mbti_type']) ?> · <?= htmlspecialchars($cert['type_name']) ?></span>
                        <span class="metric-pill"><i class="bi bi-lightning-charge" style="color:<?= $color ?>;"></i><?= htmlspecialchars($dominantDimension['winner_meta']['label']) ?></span>
                        <span class="metric-pill"><i class="bi bi-clock-history" style="color:<?= $color ?>;"></i><?= htmlspecialchars($createdAtLabel) ?></span>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px;border-radius:18px;background:linear-gradient(135deg,<?= $color ?>24,<?= $color ?>10);box-shadow:0 10px 30px <?= $color ?>18;flex-shrink:0;">
                            <span style="font-size:1.8rem;"><?= $icon ?></span>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-2" style="font-size:1.5rem;"><?= htmlspecialchars($cert['type_nickname']) ?> 的完整画像</h3>
                            <p class="mb-0" style="color:var(--text-2);line-height:1.9;"><?= htmlspecialchars($summaryParagraph) ?></p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <?php foreach ($coreKeywords as $keyword): ?>
                            <span class="keyword-chip"><i class="bi bi-check2-circle" style="color:<?= $color ?>;"></i><?= htmlspecialchars($keyword) ?></span>
                        <?php endforeach; ?>
                    </div>

                    <div class="detail-list">
                        <div class="detail-list-item">
                            <i class="bi bi-briefcase-fill"></i>
                            <div>
                                <div class="fw-semibold mb-1" style="color:var(--text-1);">你的高表现工作模式</div>
                                <div style="color:var(--text-2);line-height:1.8;"><?= htmlspecialchars($workStyle['style']) ?>，尤其适合 <span style="color:<?= $color ?>;"><?= htmlspecialchars($workStyle['env']) ?></span> 这类环境。</div>
                            </div>
                        </div>
                        <div class="detail-list-item">
                            <i class="bi bi-mortarboard-fill"></i>
                            <div>
                                <div class="fw-semibold mb-1" style="color:var(--text-1);">最容易吸收的学习方式</div>
                                <div style="color:var(--text-2);line-height:1.8;">你更适合用 <span style="color:<?= $color ?>;"><?= htmlspecialchars($learnPref['method']) ?></span> 的方式进入状态，配合 <?= htmlspecialchars($learnPref['tips']) ?> 会更稳。</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="detail-panel p-4 p-lg-5 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-bold mb-0">关键洞察</h4>
                        <span class="px-3 py-1 rounded-pill" style="background:<?= $color ?>14;color:<?= $color ?>;font-size:0.78rem;font-weight:700;"><?= $clarityScore ?>%</span>
                    </div>
                    <div class="row g-3 mb-4">
                        <?php foreach ($insightMetrics as $metric): ?>
                            <div class="col-sm-6">
                                <div class="insight-item">
                                    <div class="insight-item-label mb-2"><?= htmlspecialchars($metric['label']) ?></div>
                                    <div class="insight-item-value mb-2"><?= htmlspecialchars($metric['value']) ?></div>
                                    <div class="insight-item-note"><?= htmlspecialchars($metric['note']) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="p-3 rounded-4" style="background:linear-gradient(135deg,<?= $color ?>14,transparent);border:1px solid <?= $color ?>20;">
                        <div class="d-flex align-items-start gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center" style="width:42px;height:42px;border-radius:14px;background:<?= $color ?>1A;color:<?= $color ?>;flex-shrink:0;">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <div>
                                <div class="fw-semibold mb-1" style="color:var(--text-1);">一句话看重点</div>
                                <div style="color:var(--text-2);line-height:1.8;">你最值得优先发挥的，是 <span style="color:<?= $color ?>;"><?= htmlspecialchars($dominantDimension['winner_meta']['label']) ?></span> 这部分天然偏好。它通常是你在压力下依旧会保留下来的稳定反应模式。</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 维度拆解 ==================== -->
<section class="pb-5">
    <div class="container">
        <div class="section-title mb-2">
            <span class="section-title-line"></span>
            <span class="fw-bold" style="letter-spacing:1px;">四维结构拆解</span>
            <span class="section-title-line"></span>
        </div>
        <p class="text-center mb-4" style="color:var(--text-3);max-width:760px;margin-inline:auto;">四个维度不是平均分摊的标签，而是你思考与行动时的默认偏好。哪一边差距大，哪一边就更像你的“本能档位”。</p>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <div class="detail-panel p-4 animate-on-scroll dimension-sidebar-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="fw-bold mb-0">人格雷达图</h4>
                        <span class="px-3 py-1 rounded-pill result-soft-pill" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:var(--text-2);font-size:0.76rem;"><?= htmlspecialchars($clarityLabel) ?></span>
                    </div>

                    <div class="radar-container mb-3">
                        <canvas id="radarChart" aria-label="MBTI 维度雷达图"></canvas>
                    </div>
                    <div class="detail-list mb-4">
                        <div class="detail-list-item">
                            <i class="bi bi-bar-chart-fill"></i>
                            <div>
                                <div class="fw-semibold mb-1" style="color:var(--text-1);">偏好清晰度</div>
                                <div style="color:var(--text-2);line-height:1.8;">当前四个维度平均偏好约为 <?= $clarityScore ?>%，说明你的人格倾向属于 <span style="color:<?= $color ?>;"><?= htmlspecialchars($clarityLabel) ?></span>。</div>
                            </div>
                        </div>
                        <div class="detail-list-item">
                            <i class="bi bi-compass-fill"></i>
                            <div>
                                <div class="fw-semibold mb-1" style="color:var(--text-1);">最有辨识度的维度</div>
                                <div style="color:var(--text-2);line-height:1.8;"><?= htmlspecialchars($dominantDimension['name']) ?> 上，<?= htmlspecialchars($dominantDimension['winner_meta']['label']) ?> 相比 <?= htmlspecialchars($dominantDimension['loser_meta']['label']) ?> 领先 <?= $dominantDimension['gap'] ?> 分，这是你整体气质里最显眼的一笔。</div>
                            </div>
                        </div>
                    </div>

                    <div class="dimension-side-stack">
                        <div class="dimension-note-card">
                            <div class="dimension-note-title">四维强弱排序</div>
                            <div class="d-grid gap-2">
                                <?php foreach ($dimensionRanking as $index => $item): ?>
                                    <div class="dimension-highlight-item">
                                        <div class="dimension-highlight-head">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="dimension-rank-pill">#<?= $index + 1 ?></span>
                                                <span class="fw-semibold" style="color:var(--text-1);"><?= htmlspecialchars($item['name']) ?></span>
                                            </div>
                                            <span class="dimension-highlight-badge" style="background:<?= $item['color'] ?>14;color:<?= $item['color'] ?>;"><?= htmlspecialchars($item['winner_meta']['letter']) ?></span>
                                        </div>
                                        <div class="mini-progress mb-2"><span style="width:<?= max(18, min(100, $item['gap'] * 8)) ?>%;background:linear-gradient(90deg,<?= $item['color'] ?>66,<?= $item['color'] ?>);"></span></div>
                                        <div class="dimension-highlight-desc"><?= htmlspecialchars($item['winner_meta']['label']) ?> 领先 <?= $item['gap'] ?> 分，属于 <?= htmlspecialchars($item['intensity']) ?> 的偏好。</div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="dimension-note-card">
                            <div class="dimension-note-title">怎么看这块结果</div>
                            <div class="dimension-note-text">分差越大，说明这一维越像你的默认设置；分差越小，说明你在这一维更灵活，不容易被单一标签锁死。左边雷达图看整体轮廓，右边四张卡再看每一维的具体原因，信息就完整了。</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-7">
                <div class="row g-3">
                    <?php foreach ($dimensionScores as $item): ?>
                        <div class="col-12">
                            <div class="detail-panel p-4 dimension-card h-100" style="border-color:<?= $item['color'] ?>22;">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="d-inline-flex align-items-center justify-content-center" style="width:46px;height:46px;border-radius:14px;background:<?= $item['color'] ?>16;color:<?= $item['color'] ?>;flex-shrink:0;">
                                            <i class="bi <?= $item['icon'] ?>"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold" style="font-size:1.02rem;"><?= htmlspecialchars($item['name']) ?></div>
                                            <div style="color:var(--text-3);font-size:0.86rem;line-height:1.7;"><?= htmlspecialchars($item['summary']) ?></div>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 rounded-pill" style="background:<?= $item['color'] ?>14;color:<?= $item['color'] ?>;font-size:0.76rem;font-weight:700;"><?= htmlspecialchars($item['intensity']) ?></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:0.85rem;">
                                    <span style="color:<?= $item['color'] ?>;font-weight:700;"><?= htmlspecialchars($item['winner_meta']['label']) ?> · <?= $item['winner_score'] ?> 分</span>
                                    <span style="color:var(--text-4);"><?= htmlspecialchars($item['loser_meta']['label']) ?> · <?= $item['loser_score'] ?> 分</span>
                                </div>
                                <div class="dimension-meter mb-3"><span style="width:<?= $item['winner_pct'] ?>%;background:linear-gradient(90deg,<?= $item['color'] ?>66,<?= $item['color'] ?>);"></span></div>

                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="p-3 rounded-4 h-100 dimension-subpanel" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);">
                                            <div style="font-size:0.74rem;color:var(--text-4);letter-spacing:0.6px;text-transform:uppercase;">主导倾向</div>

                                            <div class="fw-semibold mt-1 mb-1" style="color:var(--text-1);"><?= htmlspecialchars($item['winner_meta']['label']) ?></div>
                                            <div style="font-size:0.84rem;color:var(--text-3);line-height:1.7;"><?= htmlspecialchars($item['winner_meta']['desc']) ?></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="p-3 rounded-4 h-100 dimension-subpanel" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);">
                                            <div style="font-size:0.74rem;color:var(--text-4);letter-spacing:0.6px;text-transform:uppercase;">对立面占比</div>

                                            <div class="fw-semibold mt-1 mb-1" style="color:var(--text-1);"><?= $item['loser_pct'] ?>%</div>
                                            <div style="font-size:0.84rem;color:var(--text-3);line-height:1.7;">这说明你不是单一极端类型，仍保留了 <?= htmlspecialchars($item['loser_meta']['label']) ?> 的一部分弹性。</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 人格画像 ==================== -->
<section class="pb-5">
    <div class="container">
        <div class="section-title mb-2">
            <span class="section-title-line"></span>
            <span class="fw-bold" style="letter-spacing:1px;">人格画像与成长建议</span>
            <span class="section-title-line"></span>
        </div>
        <p class="text-center mb-4" style="color:var(--text-3);max-width:760px;margin-inline:auto;">真正有用的结果页，不只是夸你是什么类型，还要告诉你优势怎么用、盲点在哪里，以及下一步怎么调得更顺手。</p>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(52,211,153,0.12);color:#34D399;"><i class="bi bi-gem"></i></span>
                        <div>
                            <div class="fw-bold">天赋优势</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">你最自然会用出来的能力</div>
                        </div>
                    </div>
                    <div class="detail-list">
                        <?php foreach ($strengthList as $strength): ?>
                            <div class="detail-list-item py-3">
                                <i class="bi bi-check2-circle"></i>
                                <div style="color:var(--text-2);line-height:1.8;"><?= htmlspecialchars($strength) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(244,114,182,0.12);color:#F472B6;"><i class="bi bi-eyeglasses"></i></span>
                        <div>
                            <div class="fw-bold">潜在盲区</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">通常在压力下最容易暴露</div>
                        </div>
                    </div>
                    <div class="detail-list">
                        <?php foreach ($weaknessList as $weakness): ?>
                            <div class="detail-list-item py-3">
                                <i class="bi bi-exclamation-circle"></i>
                                <div style="color:var(--text-2);line-height:1.8;"><?= htmlspecialchars($weakness) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(34,211,238,0.12);color:#22D3EE;"><i class="bi bi-briefcase"></i></span>
                        <div>
                            <div class="fw-bold">适配方向</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">更容易发挥优势的领域</div>
                        </div>
                    </div>
                    <div class="detail-list">
                        <?php foreach ($careerList as $career): ?>
                            <div class="detail-list-item py-3">
                                <i class="bi bi-arrow-up-right-circle"></i>
                                <div style="color:var(--text-2);line-height:1.8;"><?= htmlspecialchars($career) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(251,191,36,0.12);color:#FBBF24;"><i class="bi bi-rocket-takeoff"></i></span>
                        <div>
                            <div class="fw-bold">接下来这样做</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">把结果转成实际行动</div>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <?php foreach ($extendedTips as $index => $tip): ?>
                            <div class="action-plan-item">
                                <span class="action-plan-index" style="background:linear-gradient(135deg,<?= $color ?>,<?= $color ?>AA);"><?= $index + 1 ?></span>
                                <div style="color:var(--text-2);line-height:1.8;"><?= htmlspecialchars($tip) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== 落地场景 ==================== -->
<section class="pb-5">
    <div class="container">
        <div class="section-title mb-2">
            <span class="section-title-line"></span>
            <span class="fw-bold" style="letter-spacing:1px;">落到现实场景里看</span>
            <span class="section-title-line"></span>
        </div>
        <p class="text-center mb-4" style="color:var(--text-3);max-width:760px;margin-inline:auto;">你在工作、学习、合作和关系里的表现，往往比“我是某某类型”这个结论本身更有价值。</p>

        <div class="row g-4 align-items-stretch mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(129,140,248,0.12);color:#818CF8;"><i class="bi bi-building"></i></span>
                        <div>
                            <div class="fw-bold">工作风格</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">你怎样更容易出成果</div>
                        </div>
                    </div>
                    <div style="color:var(--text-2);line-height:1.85;">
                        <?= htmlspecialchars($workStyle['style']) ?>
                    </div>
                    <div class="mt-3 p-3 rounded-4 result-soft-panel" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);color:var(--text-3);line-height:1.8;">

                        <span style="color:var(--text-1);font-weight:600;">最适环境：</span><?= htmlspecialchars($workStyle['env']) ?><br>
                        <span style="color:var(--text-1);font-weight:600;">优势关键词：</span><?= htmlspecialchars($workStyle['strength']) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(34,211,238,0.12);color:#22D3EE;"><i class="bi bi-book"></i></span>
                        <div>
                            <div class="fw-bold">学习偏好</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">怎样学更容易学进去</div>
                        </div>
                    </div>
                    <div style="color:var(--text-2);line-height:1.85;">
                        <span style="color:var(--text-1);font-weight:600;">推荐方式：</span><?= htmlspecialchars($learnPref['method']) ?><br>
                        <span style="color:var(--text-1);font-weight:600;">偏好内容：</span><?= htmlspecialchars($learnPref['prefer']) ?>
                    </div>
                    <div class="mt-3 p-3 rounded-4 result-soft-panel" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);color:var(--text-3);line-height:1.8;">

                        <?= htmlspecialchars($learnPref['tips']) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(244,114,182,0.12);color:#F472B6;"><i class="bi bi-people-fill"></i></span>
                        <div>
                            <div class="fw-bold">团队角色</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">你在协作里天然像谁</div>
                        </div>
                    </div>
                    <div class="fw-semibold mb-2" style="color:var(--text-1);"><?= htmlspecialchars($teamRole['role']) ?></div>
                    <div style="color:var(--text-2);line-height:1.85;"><?= htmlspecialchars($teamRole['desc']) ?></div>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <?php foreach ($teamRole['strengths'] as $strength): ?>
                            <span class="keyword-chip"><i class="bi bi-lightning-fill" style="color:#F472B6;"></i><?= htmlspecialchars($strength) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-mbti report-card p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="report-icon-box" style="background:rgba(251,191,36,0.12);color:#FBBF24;"><i class="bi bi-heart-fill"></i></span>
                        <div>
                            <div class="fw-bold">关系风格</div>
                            <div style="color:var(--text-4);font-size:0.82rem;">你通常如何建立连接</div>
                        </div>
                    </div>
                    <div class="fw-semibold mb-2" style="color:var(--text-1);"><?= htmlspecialchars($relation['label']) ?></div>
                    <div style="color:var(--text-2);line-height:1.85;"><?= htmlspecialchars($relation['desc']) ?></div>
                </div>
            </div>
        </div>

        <div class="row g-4 align-items-stretch">
            <?php foreach ($compatSections as $section): ?>
                <div class="col-lg-4">
                    <div class="card-mbti compat-card p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="compat-badge <?= $section['class'] ?>"><i class="bi <?= $section['icon'] ?>"></i><?= htmlspecialchars($section['title']) ?></span>
                            <span style="font-size:0.8rem;color:var(--text-4);"><?= count($section['types']) ?> 种组合</span>
                        </div>
                        <div style="color:var(--text-3);line-height:1.8;" class="mb-3"><?= htmlspecialchars($section['desc']) ?></div>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($section['types'] as $type): ?>
                                <span class="compat-type-chip"><?= htmlspecialchars($type) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="assets/js/html2canvas.min.js" defer></script>

<!-- QRCode 生成库 — 按需懒加载（仅结果页需要） -->
<script src="assets/js/qrcode.min.js" defer></script>

<script>
// ======== 确保依赖加载完成后再执行 ========
window.addEventListener('DOMContentLoaded', function() {
    const shareUrl = '<?= htmlspecialchars($shareUrl) ?>';
    const certNo = '<?= htmlspecialchars($cert['certificate_no']) ?>';
    const certColor = '<?= $color ?>';

    // ======== 二维码生成（等待 qrcode 库就绪） ========
    function initQRCode() {
        if (typeof QRCode === 'undefined') {
            // qrcode.min.js 还没加载完，最多等 3 秒
            setTimeout(initQRCode, 100);
            return;
        }
        const container = document.getElementById('qrcodeCanvas');
        if (!container) return;
        // 清空容器
        container.innerHTML = '';
        // 创建 QR Code
        new QRCode(container, {
            text: shareUrl,
            width: 150,
            height: 150,
            colorDark: '#1a1a2e',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
        });
        // 给生成的 img 添加样式
        const qrImg = container.querySelector('img, canvas');
        if (qrImg) {
            qrImg.style.borderRadius = '8px';
            qrImg.style.display = 'block';
        }
    }
    initQRCode();

    // ======== 下载证书 ========
    window.downloadCertificate = function() {
        // 暂停浮动动画以便截图
        const card = document.getElementById('certificateCard');
        card.classList.remove('cert-float');
        card.style.transform = 'none';

        showToast('正在生成证书图片...', 'info');

        if (typeof html2canvas === 'undefined') {
            showToast('截图库还在加载中，请稍后再试', 'error');
            return;
        }

        var exportBackground = getComputedStyle(document.documentElement).getPropertyValue('--bg-0').trim() || '#0c0c14';

        html2canvas(card, {
            scale: 2,
            useCORS: true,
            backgroundColor: exportBackground,
            logging: false
        }).then(function(canvas) {

            var link = document.createElement('a');
            link.download = 'MBTI_' + certNo + '_certificate.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            showToast('证书下载成功！', 'success');
        }).catch(function(err) {
            console.error('html2canvas error:', err);
            showToast('生成失败，请尝试截图保存', 'error');
        });
    };

    // ======== 复制链接 ========
    window.copyLink = function() {
        var url = shareUrl;
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(function() {
                showToast('链接已复制到剪贴板', 'success');
            }).catch(function() { fallbackCopy(url); });
        } else {
            fallbackCopy(url);
        }
    };

    function fallbackCopy(text) {
        var input = document.createElement('input');
        input.type = 'text';
        input.value = text;
        input.style.cssText = 'position:fixed;left:-9999px;top:-9999px;opacity:0';
        document.body.appendChild(input);
        input.focus();
        input.select();
        try {
            document.execCommand('copy')
                ? showToast('链接已复制到剪贴板', 'success')
                : showToast('复制失败，请手动复制地址栏链接', 'error');
        } catch(e) {
            showToast('复制失败，请手动复制地址栏链接', 'error');
        }
        document.body.removeChild(input);
    }

    // ======== 雷达图绘制 ========
    (function drawRadarChart() {
        var canvas = document.getElementById('radarChart');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var dpr = window.devicePixelRatio || 1;
        var size = 280;
        canvas.width = size * dpr;
        canvas.height = size * dpr;
        canvas.style.width = size + 'px';
        canvas.style.height = size + 'px';
        ctx.scale(dpr, dpr);

        var cx = size / 2;
        var cy = size / 2;
        var maxR = Math.max(10, size / 2 - 40);
        var sides = 4;
        var angleStep = (Math.PI * 2) / sides;
        var startAngle = -Math.PI / 2; // 从顶部开始

        // 维度数据
        var labels = ['E/I', 'S/N', 'T/F', 'J/P'];
        var values = [<?= $eiPct ?>, <?= $snPct ?>, <?= $tfPct ?>, <?= $jpPct ?>];
        var dimColors = ['#818CF8', '#22D3EE', '#F472B6', '#FBBF24'];

        // 动画参数
        var progress = 0;
        var animDuration = 1200; // ms
        var animStart = performance.now();

        function getThemeVar(name, fallback) {
            var value = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
            return value || fallback;
        }

        function colorToRgba(color, alpha) {
            var value = (color || '').trim();
            if (!value) return 'rgba(129,140,248,' + alpha + ')';

            if (value.indexOf('rgba(') === 0) {
                var rgbaMatch = value.match(/rgba\((\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i);
                if (rgbaMatch) {
                    return 'rgba(' + rgbaMatch[1] + ',' + rgbaMatch[2] + ',' + rgbaMatch[3] + ',' + alpha + ')';
                }
            }

            if (value.indexOf('rgb(') === 0) {
                var rgbMatch = value.match(/rgb\((\d+)\s*,\s*(\d+)\s*,\s*(\d+)\)/i);
                if (rgbMatch) {
                    return 'rgba(' + rgbMatch[1] + ',' + rgbMatch[2] + ',' + rgbMatch[3] + ',' + alpha + ')';
                }
            }

            if (value.charAt(0) === '#') {
                var hex = value.slice(1);
                if (hex.length === 3) {
                    hex = hex.split('').map(function(ch) { return ch + ch; }).join('');
                }
                if (hex.length === 6) {
                    var r = parseInt(hex.slice(0, 2), 16);
                    var g = parseInt(hex.slice(2, 4), 16);
                    var b = parseInt(hex.slice(4, 6), 16);
                    return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
                }
            }

            return value;
        }

        function getRadarPalette() {
            var primary = getThemeVar('--primary', '#818CF8');
            var rose = getThemeVar('--rose', '#F472B6');
            var cyan = getThemeVar('--cyan', '#22D3EE');

            return {
                grid: [
                    colorToRgba(primary, 0.08),
                    colorToRgba(primary, 0.12),
                    colorToRgba(primary, 0.16)
                ],
                axis: colorToRgba(primary, 0.14),
                areaStart: colorToRgba(primary, 0.28),
                areaMid: colorToRgba(rose, 0.14),
                areaEnd: colorToRgba(cyan, 0.08),
                outline: colorToRgba(primary, 0.55),
                pointStroke: getThemeVar('--bg-2', '#12121E'),
                valueText: getThemeVar('--text-3', '#6B6B85')
            };
        }

        function easeOutCubic(t) {

            return 1 - Math.pow(1 - t, 3);
        }

        function getPoint(index, radius) {
            var angle = startAngle + index * angleStep;
            return {
                x: cx + Math.cos(angle) * radius,
                y: cy + Math.sin(angle) * radius
            };
        }

        function draw(timestamp) {
            var elapsed = timestamp - animStart;
            progress = Math.min(1, elapsed / animDuration);
            var easedProgress = easeOutCubic(progress);

            ctx.clearRect(0, 0, size, size);

            var palette = getRadarPalette();

            // 绘制背景网格（3层）
            var levels = [0.33, 0.66, 1.0];
            levels.forEach(function(level, li) {
                var r = Math.max(1, maxR * level);
                ctx.beginPath();
                for (var i = 0; i <= sides; i++) {
                    var p = getPoint(i % sides, r);
                    if (i === 0) ctx.moveTo(p.x, p.y);
                    else ctx.lineTo(p.x, p.y);
                }
                ctx.closePath();
                ctx.strokeStyle = palette.grid[li] || palette.grid[palette.grid.length - 1];
                ctx.lineWidth = 1;
                ctx.stroke();
            });

            // 绘制轴线
            for (var i = 0; i < sides; i++) {
                var p = getPoint(i, maxR);
                ctx.beginPath();
                ctx.moveTo(cx, cy);
                ctx.lineTo(p.x, p.y);
                ctx.strokeStyle = palette.axis;
                ctx.lineWidth = 1;
                ctx.stroke();
            }

            // 绘制数据区域
            var dataPoints = [];
            for (var i = 0; i < sides; i++) {
                var val = (values[i] / 100) * easedProgress;
                var r = Math.max(1, maxR * val);
                dataPoints.push(getPoint(i, r));
            }

            // 填充渐变区域
            ctx.beginPath();
            dataPoints.forEach(function(p, i) {
                if (i === 0) ctx.moveTo(p.x, p.y);
                else ctx.lineTo(p.x, p.y);
            });
            ctx.closePath();

            // 径向渐变
            var gradient = ctx.createRadialGradient(cx, cy, 0, cx, cy, maxR);
            gradient.addColorStop(0, palette.areaStart);
            gradient.addColorStop(0.5, palette.areaMid);
            gradient.addColorStop(1, palette.areaEnd);
            ctx.fillStyle = gradient;
            ctx.fill();

            // 边框线
            ctx.beginPath();
            dataPoints.forEach(function(p, i) {
                if (i === 0) ctx.moveTo(p.x, p.y);
                else ctx.lineTo(p.x, p.y);
            });
            ctx.closePath();
            ctx.strokeStyle = palette.outline;
            ctx.lineWidth = 2;
            ctx.stroke();

            // 绘制数据点
            dataPoints.forEach(function(p, i) {
                ctx.beginPath();
                ctx.arc(p.x, p.y, 4, 0, Math.PI * 2);
                ctx.fillStyle = dimColors[i];
                ctx.fill();
                ctx.strokeStyle = palette.pointStroke;
                ctx.lineWidth = 1.5;
                ctx.stroke();
            });


            // 绘制标签
            for (var i = 0; i < sides; i++) {
                var labelR = maxR + 22;
                var p = getPoint(i, labelR);

                // 维度名
                ctx.font = '700 13px "DouyinSansSubset", "DouyinSansFull"';

                ctx.fillStyle = dimColors[i];
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(labels[i], p.x, p.y - 7);

                // 百分比
                var displayVal = Math.round(values[i] * easedProgress);
                ctx.font = '400 11px "DouyinSansSubset", "DouyinSansFull"';

                ctx.fillStyle = palette.valueText;
                ctx.fillText(displayVal + '%', p.x, p.y + 8);


            }

            if (progress < 1) {
                requestAnimationFrame(draw);
            }
        }

        // 使用 IntersectionObserver 触发动画
        var parentCard = canvas.closest('.animate-on-scroll');
        if (parentCard) {
            parentCard.style.setProperty('--radar-force-visible', '1');
            parentCard.style.opacity = '1';
            parentCard.style.transform = 'none';
            parentCard.classList.add('is-visible');
        }

        var hasStarted = false;
        var observer;

        function rerenderRadarChart() {
            animStart = performance.now();
            progress = 0;
            requestAnimationFrame(draw);
        }

        function startRadarAnimation() {
            if (hasStarted) return;
            hasStarted = true;
            rerenderRadarChart();
            if (observer) observer.disconnect();
        }

        window.addEventListener('bugcool:themechange', function() {
            rerenderRadarChart();
        });

        function ensureRadarFontsThenStart() {

            if (document.fonts && document.fonts.load) {
                Promise.all([
                    document.fonts.load('700 13px "DouyinSansSubset"'),
                    document.fonts.load('400 11px "DouyinSansSubset"')
                ]).catch(function() {
                    // 字体加载失败时也不要阻塞图表渲染
                }).finally(startRadarAnimation);
                return;
            }

            startRadarAnimation();
        }


        observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    ensureRadarFontsThenStart();
                }
            });
        }, { threshold: 0.3 });
        observer.observe(canvas);

        // 兜底：如果 2 秒后还没触发（已在视口内），强制绘制
        setTimeout(function() {
            if (!hasStarted) {
                ensureRadarFontsThenStart();
            }
        }, 2000);

    })();
});
</script>

<?php include_once __DIR__ . '/includes/footer.php'; ?>
