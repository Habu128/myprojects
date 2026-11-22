<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\CitizenFeedbackController;
use App\Http\Controllers\CouncilProjectsController;
use App\Http\Controllers\CouncilReportsController;
use App\Http\Controllers\PublicProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $localGovernmentName = 'Shani Local Government';

    return view('welcome', [
        'localGovernmentName' => $localGovernmentName,
        'assistantConfigured' => filled(config('services.openai.key')),
    ]);
})->name('home');

$publicPages = [
    'about' => [
        'path' => '/about',
        'eyebrow' => 'ABOUT SHANI LGA',
        'title' => 'Shani Local Government Area.',
        'description' => 'Shani is a Local Government Area in Borno State, north-eastern Nigeria, and part of Borno South. Shani town is the administrative headquarters.',
        'details' => 'This overview brings together sourced public information about Shani’s location, people, wards, local administration, livelihoods, and traditional institution. Records differ by date and publisher; each figure below includes its source and reference period.',
        'items' => [
            ['title' => 'Location & administration', 'description' => 'Shani is one of Borno State’s 27 Local Government Areas and is grouped with Borno South. The local council is the grassroots tier for community development, service delivery, and public engagement.', 'route' => 'services', 'link_text' => 'Explore local services'],
            ['title' => 'Administrative headquarters', 'description' => 'The headquarters is in Shani town. INEC’s Shani LGA office locator places its office in Shani, behind Mega Primary School. This is an electoral-office location, not the council secretariat address.', 'route' => 'map', 'link_text' => 'Explore the ward map'],
            ['title' => 'Population baseline', 'description' => 'The National Population Commission’s 2006 census recorded 100,989 residents in Shani LGA. This is a historical census count, not a current population estimate.'],
            ['title' => 'Eleven registration areas (wards)', 'description' => 'INEC’s 2015 polling-unit directory lists Bargu / Burashika, Buma, Gasi / Salifawa, Gora, Gwalasho, Gwaskara, Kombo, Kubo, Kwaba, Shani, and Walama. Spelling and labels can vary across newer datasets.', 'route' => 'map', 'link_text' => 'View the interactive map'],
            ['title' => 'Council responsibilities', 'description' => 'Borno State describes local council responsibilities as including community development, local revenue collection, primary healthcare, basic education, and community participation.', 'route' => 'services', 'link_text' => 'Browse service information'],
            ['title' => 'Communities & livelihoods', 'description' => 'A 2020 socio-economic survey sampled farming communities in Shani and four other Borno LGAs. In the surveyed communities, farming was reported as a primary occupation and income source. The study is a sample, not a full economic profile of Shani.'],
            ['title' => 'Traditional institution', 'description' => 'Shani has a traditional Emirate institution. Read the documented account and source notes, including the gaps in the available online record.', 'route' => 'emir-history'],
            ['title' => 'Political & administrative history', 'description' => 'Review documented changes to local administration and the distinction between the local government council and the traditional institution.', 'route' => 'political-history'],
            ['title' => 'Budgets, projects & public records', 'description' => 'See BudgIT’s assessment and Borno State’s published local-government budget and audit records. Budgeted projects are plans, not proof of completion.', 'route' => 'transparency'],
            ['title' => 'Resident feedback', 'description' => 'Send a ward-tagged enquiry or suggest a correction to the public information on this site.', 'route' => 'feedback', 'link_text' => 'Send feedback'],
            ['title' => 'Council directory', 'description' => 'See the leadership information currently supported by public sources and the council details still awaiting official confirmation.', 'route' => 'council'],
            ['title' => 'News & public notices', 'description' => 'Official announcements will be listed here when published and verified.', 'route' => 'updates'],
        ],
        'sources' => [
            ['title' => 'National Bureau of Statistics: Borno population by LGA, 1991 and 2006 (source: National Population Commission)', 'url' => 'https://nigerianstat.gov.ng/pdfuploads/Annual_Abstract_of_Statistics_2011.pdf'],
            ['title' => 'INEC: Borno polling-unit directory, revised January 2015 (Shani Registration Areas)', 'url' => 'https://wp1.inecnigeria.org/wp-content/uploads/2019/02/PU_Directory_Revised_January_2015_Borno.pdf'],
            ['title' => 'Borno State Government: Local Government Administration and LGA responsibilities', 'url' => 'https://bornostate.gov.ng/wb/local-govt-administration'],
            ['title' => 'Borno State Government: Medium Term Expenditure Framework 2025–2027 (Borno South)', 'url' => 'https://pfm.bo.gov.ng/wp-content/uploads/2024/09/Borno%20State%20Final%20MTEF%20WORD%202025%20-2027%20MTEF%20FINAL%2030.09.2024.pdf'],
            ['title' => 'Socio-economic survey of farming communities in Shani and other Borno LGAs (2020)', 'url' => 'https://jaeess.com.ng/index.php/jaeess/article/view/115'],
        ],
    ],
    'emir-history' => [
        'path' => '/about/emirate-history',
        'eyebrow' => 'TRADITION & LEADERSHIP',
        'title' => 'History of the Shani Emirate.',
        'description' => 'A concise account of Shani’s traditional institution, based on the published sources listed below.',
        'details' => 'The written record available online is incomplete. The account below does not replace oral histories held by the palace, families, elders, or community historians. Names and dates should be supplemented as those records are confirmed.',
        'items' => [
            ['title' => 'A traditional institution in southern Borno', 'description' => 'A World Bank project assessment describes Shani as one of the southern Borno chiefdoms established during the twentieth century. It records the family history of Shani’s traditional leadership as beginning with Bila Wati.'],
            ['title' => 'The Emir of Shani', 'description' => 'Borno State’s 2025–2027 Medium Term Expenditure Framework lists the Emir of Shani among the state’s traditional rulers in southern Borno.'],
            ['title' => 'Muhammadu Mailafiya and succession', 'description' => 'Contemporary reports state that Alhaji Muhammadu Mailafiya was on the throne from 1982 until his death in December 2013. His son, Nasiru Mailafiya, was installed in January 2014.'],
            ['title' => 'First-class recognition', 'description' => 'A 2019 report records the Borno State Governor’s promotion of the Emir of Shani from second-class to first-class status.'],
        ],
        'sources' => [
            ['title' => 'World Bank: Final ESIA of the Gombe–Biu Road', 'url' => 'https://documents1.worldbank.org/curated/en/803811468110345056/pdf/E15760EA0v150B0ed01002401200P090135.pdf'],
            ['title' => 'Borno State Government: Medium Term Expenditure Framework 2025–2027', 'url' => 'https://pfm.bo.gov.ng/wp-content/uploads/2024/09/Borno%20State%20Final%20MTEF%20WORD%202025%20-2027%20MTEF%20FINAL%2030.09.2024.pdf'],
            ['title' => 'The Tide: Nasiru Mailafiya installed as Emir in January 2014', 'url' => 'https://www.thetidenewsonline.com/2014/01/the-states-432/'],
            ['title' => 'The Nation: Borno promotes three emirs, January 2019', 'url' => 'https://thenationonlineng.net/shettima-promotes-three-emirs-borno/'],
        ],
    ],
    'political-history' => [
        'path' => '/about/political-history',
        'eyebrow' => 'ADMINISTRATION & PUBLIC LIFE',
        'title' => 'Political history of Shani.',
        'description' => 'Shani’s political story includes traditional authorities, colonial-era administrative proposals, and the development of modern local government.',
        'details' => 'This timeline uses published sources and distinguishes proposals from changes that were actually implemented. The sources reviewed do not establish a reliable exact creation date for Shani Local Government Area.',
        'items' => [
            ['title' => 'Traditional authorities and colonial administration', 'description' => 'A historical study records that a 1957 Northern Regional Legal Notice proposed a Biu Federal Native Authority made up of the Biu, Shani, and Askira Native Authorities. The study says the proposal was not implemented.'],
            ['title' => 'Borno State, 1976', 'description' => 'Borno State Government records that modern Borno was carved out of the former North-Eastern State in 1976. The state now has 27 Local Government Areas, including Shani.'],
            ['title' => 'A boundary change in 1991', 'description' => 'A study of Hawul Local Government records that Hawul was created from the former Shani Local Government Area in 1991, changing the administrative boundaries of the area.'],
            ['title' => 'Shani today', 'description' => 'Shani is one of Borno State’s Local Government Areas. Its local government administration and its traditional emirate institution are distinct parts of public life. Explore the current ward map for the area’s 11 wards.', 'route' => 'map', 'link_text' => 'View the ward map'],
        ],
        'sources' => [
            ['title' => 'Historical study citing Northern Regional Legal Notice 469 of 1957', 'url' => 'https://site-cu4j2x8y.wsecdn1.websitecdn.com/uploads/9cd1ef60beab452eb5885cd0c742cb68.pdf/Interrogating%2Bthe%2BQuest%2Bfor%2BCreation%2Bof%2Ba%2BMiddle%2BBelt%2BState%2Bin%2BColonial%2BNigeria?v=260402090133'],
            ['title' => 'Borno State Government: Medium Term Expenditure Framework 2025–2027', 'url' => 'https://pfm.bo.gov.ng/wp-content/uploads/2024/09/Borno%20State%20Final%20MTEF%20WORD%202025%20-2027%20MTEF%20FINAL%2030.09.2024.pdf'],
            ['title' => 'Borno State Government: Harmonised Local Government Budget Manual', 'url' => 'https://pfm.bo.gov.ng/wp-content/uploads/HARMONISED-BORNO-STATE-LOCAL-GOVERNMENT-BUDGET-MANUAL.pdf'],
            ['title' => 'Hawul Local Government study: creation from the former Shani LGA', 'url' => 'https://teras.ng/api/asset/document/296f88aa-8578-4e68-a7fa-824c08ffae30'],
        ],
    ],
    'services' => [
        'path' => '/services',
        'eyebrow' => 'INFORMATION & SUPPORT',
        'title' => 'Services and local responsibilities',
        'description' => 'Borno State describes local government responsibilities in areas including community development, local revenue, primary healthcare, basic education and public participation. Confirm current services and requirements with the responsible office.',
        'details' => 'This page provides general service information. Current availability, fees and application requirements should be confirmed with the responsible government office.',
        'items' => [
            ['title' => 'Primary healthcare', 'description' => 'Find primary healthcare information through the responsible state health service. Facility availability and opening hours should be confirmed before travel.', 'route' => 'feedback', 'link_text' => 'Report a health facility issue'],
            ['title' => 'Basic education', 'description' => 'Borno’s 2024–25 education baseline records 31,377 students enrolled in Shani. Use the state education channels for current school admissions and support information.', 'route' => 'transparency', 'link_text' => 'See public education records'],
            ['title' => 'Roads, water & public facilities', 'description' => 'Report a broken borehole, damaged local road, or community facility issue. Select the ward so the location can be reviewed.', 'route' => 'feedback', 'link_text' => 'Report an issue'],
            ['title' => 'Agriculture & livelihoods', 'description' => 'Ask the responsible state or local office about current agricultural support, extension services, and livelihood programmes. No unverified application details are published here.', 'route' => 'contact', 'link_text' => 'View verified contact status'],
            ['title' => 'Council administration', 'description' => 'Review published council leadership information and see which contact details still need official confirmation.', 'route' => 'council', 'link_text' => 'View the council directory'],
            ['title' => 'Budgets & public projects', 'description' => 'Open public budget and audit records, and compare project plans with implementation evidence.', 'route' => 'transparency', 'link_text' => 'Browse budgets and records'],
        ],
    ],
    'updates' => [
        'path' => '/news',
        'eyebrow' => 'FROM THE COUNCIL',
        'title' => 'News and public notices',
        'description' => 'No public notices are currently listed.',
        'details' => 'New notices will be added when verified. This page does not yet list a verified council phone number.',
        'items' => [],
    ],
    'contact' => [
        'path' => '/contact',
        'eyebrow' => 'GET IN TOUCH',
        'title' => 'Contact information',
        'description' => 'Verified council contact details are not currently available on this page. Use the feedback form for non-urgent website enquiries.',
        'details' => 'Council contact details have not been verified for publication.',
        'items' => [
            ['title' => 'Address', 'description' => 'Not published'],
            ['title' => 'Phone', 'description' => 'Not published'],
            ['title' => 'Email', 'description' => 'Not published'],
            ['title' => 'Office hours', 'description' => 'Not published'],
        ],
    ],
];

foreach ($publicPages as $routeName => $page) {
    Route::get($page['path'], function () use ($page) {
        return view('pages.show', [
            'localGovernmentName' => 'Shani Local Government',
            'page' => $page,
        ]);
    })->name($routeName);
}

Route::get('/map', function () {
    return view('pages.map', [
        'localGovernmentName' => 'Shani Local Government',
    ]);
})->name('map');

Route::get('/feedback', [CitizenFeedbackController::class, 'create'])->name('feedback');
Route::post('/feedback', [CitizenFeedbackController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('feedback.store');
Route::post('/feedback/track', [CitizenFeedbackController::class, 'track'])
    ->middleware('throttle:10,1')
    ->name('feedback.track');

Route::get('/transparency', function () {
    return view('pages.transparency', [
        'localGovernmentName' => 'Shani Local Government',
    ]);
})->name('transparency');

Route::get('/projects', [PublicProjectController::class, 'index'])->name('projects');

Route::get('/council', function () {
    return view('pages.council', [
        'localGovernmentName' => 'Shani Local Government',
    ]);
})->name('council');

Route::get('/council/staff/login', [CouncilReportsController::class, 'login'])->name('council.reports.login');
Route::post('/council/staff/login', [CouncilReportsController::class, 'authenticate'])
    ->middleware('throttle:5,1')
    ->name('council.reports.authenticate');
Route::middleware('auth')->prefix('/council/staff')->name('council.reports.')->group(function () {
    Route::get('/reports', [CouncilReportsController::class, 'index'])->name('index');
    Route::patch('/reports/{enquiry}', [CouncilReportsController::class, 'update'])->name('update');
    Route::post('/logout', [CouncilReportsController::class, 'logout'])->name('logout');
});
Route::middleware('auth')->prefix('/council/staff/projects')->name('council.projects.')->group(function () {
    Route::get('/', [CouncilProjectsController::class, 'index'])->name('index');
    Route::get('/create', [CouncilProjectsController::class, 'create'])->name('create');
    Route::post('/', [CouncilProjectsController::class, 'store'])->name('store');
    Route::get('/{councilProject}/edit', [CouncilProjectsController::class, 'edit'])->name('edit');
    Route::put('/{councilProject}', [CouncilProjectsController::class, 'update'])->name('update');
    Route::patch('/{councilProject}/archive', [CouncilProjectsController::class, 'archive'])->name('archive');
});

Route::post('/assistant/chat', AssistantController::class)
    ->middleware('throttle:10,1')
    ->name('assistant.chat');
