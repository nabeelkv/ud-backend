<?php

include("init.php");
include("simple_html_dom.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $UdemyLink = $_POST['udemyURL'];
    $getInstructorName = $_POST['instructorName'];
    $getcourseType = $_POST['courseType'];
    $getcourseDuration = $_POST['courseDuration'];
    
    $udemy_html = file_get_html($UdemyLink);
    
    if(!$udemy_html) {
     
        echo "<script>alert('Unable to load Udemy website')</script>";
     
    } else {
        
        $courseId = $udemy_html->find('body')[0]->attr["data-clp-course-id"];
		$courseTopic = $udemy_html->find('.topic-menu.udlite-breadcrumb a')[2]->plaintext;
        // $courseLang = trim($udemy_html->find('.clp-lead__element-item.clp-lead__locale',0)->plaintext);
        // $courseContents = $udemy_html->find('.curriculum--content-length--1XzLS',0)->plaintext;
           
        $isBestSeller = $udemy_html->find('.udlite-badge-bestseller',0)->plaintext;
        if($isBestSeller !== null) {$isBestSeller = 1;} else {$isBestSeller = 0;}
        
        $json = file_get_contents('https://www.udemy.com/api-2.0/courses/' . $courseId . '/?fields[course]=id,title,headline,description,created,is_paid,num_subscribers,num_reviews,avg_rating,locale,num_lectures,num_quizzes,price,primary_category,primary_subcategory,visible_instructors,image_750x422');
		$array = json_decode($json);
		
		$courseName = trim($array->title);
		$courseHeadline = trim($array->headline);
		$courseDescription = trim($array->description);
		$courseImage = trim($array->image_750x422);
		$courseCat1 = trim($array->primary_category->title);
        $courseCat2 = trim($array->primary_subcategory->title);
        $courseCat2 = trim($array->primary_subcategory->title);
        $courseLanguage = trim($array->locale->simple_english_title);
        
        // $courseCat1 = str_replace(" ","-",$courseCat1);  //this will replace space with dashes after trim
        // $courseCat1 = str_replace("&","and",$courseCat1); //this will replace & with "and" letters

        if($courseTopic === null) {
	        $courseTopic = $courseCat2;
	     } 

		$studentCount = number_format($array->num_subscribers,0,',',',');
		$reviewsCount = number_format($array->num_reviews,0,',',',');
		$avgRating = $array->avg_rating;
		$courseInstructors = count($array->visible_instructors);
		$lecturesCount = number_format($array->num_lectures,0,',',',');
        $courseDurationInHours = trim($getcourseDuration);
		$courseReleasedDate = date('F d, Y', strtotime($array->created));
		
		$mainInstructorName = $getInstructorName;
		$mainInstructorImage = $array->visible_instructors[0]->image_100x100;
		$mainInstructorPerma = $array->visible_instructors[0]->url;
        $mainInstructorDescription = $udemy_html->find('div[data-purpose=description-content]', 0);
        
        $UdemyLink = "https://click.linksynergy.com/deeplink?id=FNxb/pO*KcU&mid=39197&murl=$UdemyLink";

        
        if($courseLanguage === 'English') {
           $output = '<div dir="ltr"><div class="udmg-top-container">'.$courseHeadline.'<br/>Instructed by: <a href="#instructor">'.$mainInstructorName. '</a> | Topic: <a href="/search/label/'.$courseTopic.' Courses?max-results=10">'.$courseTopic.'</a></div>';
           $output .= '<div class="udmg-course-thumb" style="max-width:1080px;margin:0 auto;" onclick="loadVideoAjax('.$courseId.')"><div style="position: relative;padding-bottom: 56.25%; height: 0; overflow: hidden;"><img border="0" src="'.$courseImage.'" width="520" alt="'.$courseName.'" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; max-width: 1080px; max-height: 720px;"/></div><button class="udmg-play-btn"><i class="fa fa-play" aria-hidden="true"></i> Watch Preview</button></div><br/><div class="udmg-about-course"><h3>About This Course</h3><div><div class="udmg-about-course-body">'.$courseDescription.'</div></div></div><div class="udmg-course-btn"><a href="'.$UdemyLink.'" rel="nofollow" target="_blank" class="button btn link udmg-course-btn-link">Take this course</a></div><div class="udmg-course-instructor"> <h3 id="instructor">Course Instructor</h3><div class="instructor-response-author"><img data-purpose="avatar" alt="'.$mainInstructorName.'" class="udlite-avatar udlite-avatar-image" width="48" height="48" src="'.$mainInstructorImage.'"><div class="instructor-response-author-content"><p class="udlite-heading-md instructor-response-name" data-purpose="instructor-name"><a class="udmg-instuctor-title-link" href="/search/label/'.$mainInstructorName.' Courses?max-results=10"><span class="udmg-instuctor-title">'.$mainInstructorName.'</span></a></p></div></div><div class="udmg-course-instructor-body">'.$mainInstructorDescription.'</div><ul><li><a class="udmg-external-link" href="https://www.udemy.com'.$mainInstructorPerma.'">Explore '.$mainInstructorName.'\'s Courses on Udemy</a></li></ul></div><div class="udmg-course-info"> <h3>Course Info</h3><ul><li>Level: <b>Beginner</b></li><li><b>'.$lecturesCount.'</b> Lectures</li><li><b>'.$courseDurationInHours.'</b> Hours</li><li>Topic: <a href="/search/label/'.$courseTopic.' Courses?max-results=10">'.$courseTopic.'</a></li><li>Language: <a href="/search/label/'.$courseLanguage.'?max-results=10">'.$courseLanguage.'</a></li><li>Category: <a href="/search/label/'.$courseCat1.' Courses?max-results=10">'.$courseCat1.'</a>, <a href="/search/label/'.$courseCat2.' Courses?max-results=10">'.$courseCat2.'</a></li><li>Created on: <b>'.$courseReleasedDate.'</b></li><li>Provider: <a href="/search/label/Udemy?max-results=10">Udemy</a></li></ul></div></div>';
        } else {
           $output = '<div dir="ltr"><div class="udmg-top-container">'.$courseHeadline.'<br/>Instructed by: <a href="#instructor">'.$mainInstructorName. '</a> | Topic: <a href="/search/label/'.$courseTopic.' Courses ('.$courseLanguage.')?max-results=10">'.$courseTopic.'</a></div>';
           $output .= '<div class="udmg-course-thumb" style="max-width:1080px;margin:0 auto;" onclick="loadVideoAjax('.$courseId.')"><div style="position: relative;padding-bottom: 56.25%; height: 0; overflow: hidden;"><img border="0" src="'.$courseImage.'" width="520" alt="'.$courseName.'" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; max-width: 1080px; max-height: 720px;"/></div><button class="udmg-play-btn"><i class="fa fa-play" aria-hidden="true"></i> Watch Preview</button></div><br/><div class="udmg-about-course"><h3>About This Course</h3><div><div class="udmg-about-course-body">'.$courseDescription.'</div></div></div><div class="udmg-course-btn"><a href="'.$UdemyLink.'" rel="nofollow" target="_blank" class="button btn link udmg-course-btn-link">Enroll on provider\'s site</a></div><div class="udmg-course-instructor"> <h3 id="instructor">Course Instructor</h3><div class="instructor-response-author"><img data-purpose="avatar" alt="'.$mainInstructorName.'" class="udlite-avatar udlite-avatar-image" width="48" height="48" src="'.$mainInstructorImage.'"><div class="instructor-response-author-content"><p class="udlite-heading-md instructor-response-name" data-purpose="instructor-name"><a class="udmg-instuctor-title-link" href="/search/label/'.$mainInstructorName.' Courses?max-results=10"><span class="udmg-instuctor-title">'.$mainInstructorName.'</span></a></p></div></div><div class="udmg-course-instructor-body">'.$mainInstructorDescription.'</div><ul><li><a class="udmg-external-link" href="https://www.udemy.com'.$mainInstructorPerma.'">Explore '.$mainInstructorName.'\'s Courses on Udemy</a></li></ul></div><div class="udmg-course-info"> <h3>Course Info</h3><ul><li>Level: <b>Beginner</b></li><li><b>'.$lecturesCount.'</b> Lectures</li><li><b>'.$courseDurationInHours.'</b> Hours</li><li>Topic: <a href="/search/label/'.$courseTopic.' Courses ('.$courseLanguage.')?max-results=10">'.$courseTopic.'</a></li><li>Language: <a href="/search/label/'.$courseLanguage.'?max-results=10">'.$courseLanguage.'</a></li><li>Category: <a href="/search/label/'.$courseCat1.' Courses ('.$courseLanguage.')?max-results=10">'.$courseCat1.'</a>, <a href="/search/label/'.$courseCat2.' Courses ('.$courseLanguage.')?max-results=10">'.$courseCat2.'</a></li><li>Created on: <b>'.$courseReleasedDate.'</b></li><li>Provider: <a href="/search/label/Udemy?max-results=10">Udemy</a></li></ul></div></div>';
        }
    }

    
}

?>




<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="The most popular HTML, CSS, and JS library in the world.">
<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
<meta name="generator" content="Hugo 0.88.1">

<meta name="docsearch:language" content="en">
<meta name="docsearch:version" content="5.1">

<title>Tag Manager</title>

<link rel="canonical" href="https://getbootstrap.com/">



<!-- Bootstrap core CSS -->
<link href="https://getbootstrap.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<link href="https://getbootstrap.com/docs/5.1/assets/css/docs.css" rel="stylesheet">

<!-- Favicons -->
<!-- Favicons -->
<link rel="apple-touch-icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
<link rel="icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/favicon.ico">
<meta name="theme-color" content="#7952b3">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@getbootstrap">
<meta name="twitter:creator" content="@getbootstrap">
<meta name="twitter:title" content="Bootstrap">
<meta name="twitter:description" content="The most popular HTML, CSS, and JS library in the world.">
<meta name="twitter:image" content="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-social-logo.png">

<!-- Facebook -->
<meta property="og:url" content="https://getbootstrap.com/">
<meta property="og:title" content="Bootstrap">
<meta property="og:description" content="The most popular HTML, CSS, and JS library in the world.">
<meta property="og:type" content="website">
<meta property="og:image" content="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-social.png">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1000">
<meta property="og:image:height" content="500">


  </head>
  <body>
    <div class="skippy visually-hidden-focusable overflow-hidden">
  <div class="container-xl">
    <a class="d-inline-flex p-2 m-1" href="#content">Skip to main content</a>
    
  </div>
</div>


    <header class="navbar navbar-expand-md navbar-dark bd-navbar">
  <nav class="container-xxl flex-wrap flex-md-nowrap" aria-label="Main navigation">
    <a class="navbar-brand p-0 me-2" href="/udamigo/udemy/" aria-label="Bootstrap">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="d-block my-1" viewBox="0 0 118 94" role="img"><a href="udamigo/udemy/"><title>Tag Manager</title></a><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"/></svg>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" class="bi" fill="currentColor" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M2.5 11.5A.5.5 0 0 1 3 11h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 7h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 3 3h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
</svg>

    </button>

    <div class="collapse navbar-collapse" id="bdNavbar">
      <ul class="navbar-nav flex-row flex-wrap bd-navbar-nav pt-2 py-md-0">
        <li class="nav-item col-6 col-md-auto">
          <a class="nav-link p-2 active" aria-current="page" href="/" onclick="ga('send', 'event', 'Navbar', 'Community links', 'Bootstrap');">Tag Manager</a>
        </li>
        <!--<li class="nav-item col-6 col-md-auto">-->
        <!--  <a class="nav-link p-2" href="/docs/5.1/getting-started/introduction/" onclick="ga('send', 'event', 'Navbar', 'Community links', 'Docs');">Docs</a>-->
        <!--</li>-->
        <!--<li class="nav-item col-6 col-md-auto">-->
        <!--  <a class="nav-link p-2" href="/docs/5.1/examples/" onclick="ga('send', 'event', 'Navbar', 'Community links', 'Examples');">Examples</a>-->
        <!--</li>-->
        <!--<li class="nav-item col-6 col-md-auto">-->
        <!--  <a class="nav-link p-2" href="https://icons.getbootstrap.com/" onclick="ga('send', 'event', 'Navbar', 'Community links', 'Icons');" target="_blank" rel="noopener">Icons</a>-->
        <!--</li>-->
        <!--<li class="nav-item col-6 col-md-auto">-->
        <!--  <a class="nav-link p-2" href="https://themes.getbootstrap.com/" onclick="ga('send', 'event', 'Navbar', 'Community links', 'Themes');" target="_blank" rel="noopener">Themes</a>-->
        <!--</li>-->
        <!--<li class="nav-item col-6 col-md-auto">-->
        <!--  <a class="nav-link p-2" href="https://blog.getbootstrap.com/" onclick="ga('send', 'event', 'Navbar', 'Community links', 'Blog');" target="_blank" rel="noopener">Blog</a>-->
        <!--</li>-->
      </ul>

      <hr class="d-md-none text-white-50">

      <!--<ul class="navbar-nav flex-row flex-wrap ms-md-auto">-->
      <!--  <li class="nav-item col-6 col-md-auto">-->
      <!--    <a class="nav-link p-2" href="https://github.com/twbs" target="_blank" rel="noopener">-->
      <!--      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" class="navbar-nav-svg d-inline-block align-text-top" viewBox="0 0 512 499.36" role="img"><title>GitHub</title><path fill="currentColor" fill-rule="evenodd" d="M256 0C114.64 0 0 114.61 0 256c0 113.09 73.34 209 175.08 242.9 12.8 2.35 17.47-5.56 17.47-12.34 0-6.08-.22-22.18-.35-43.54-71.2 15.49-86.2-34.34-86.2-34.34-11.64-29.57-28.42-37.45-28.42-37.45-23.27-15.84 1.73-15.55 1.73-15.55 25.69 1.81 39.21 26.38 39.21 26.38 22.84 39.12 59.92 27.82 74.5 21.27 2.33-16.54 8.94-27.82 16.25-34.22-56.84-6.43-116.6-28.43-116.6-126.49 0-27.95 10-50.8 26.35-68.69-2.63-6.48-11.42-32.5 2.51-67.75 0 0 21.49-6.88 70.4 26.24a242.65 242.65 0 0 1 128.18 0c48.87-33.13 70.33-26.24 70.33-26.24 14 35.25 5.18 61.27 2.55 67.75 16.41 17.9 26.31 40.75 26.31 68.69 0 98.35-59.85 120-116.88 126.32 9.19 7.9 17.38 23.53 17.38 47.41 0 34.22-.31 61.83-.31 70.23 0 6.85 4.61 14.81 17.6 12.31C438.72 464.97 512 369.08 512 256.02 512 114.62 397.37 0 256 0z"/></svg>-->
      <!--      <small class="d-md-none ms-2">GitHub</small>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="nav-item col-6 col-md-auto">-->
      <!--    <a class="nav-link p-2" href="https://twitter.com/getbootstrap" target="_blank" rel="noopener">-->
      <!--      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" class="navbar-nav-svg d-inline-block align-text-top" viewBox="0 0 512 416.32" role="img"><title>Twitter</title><path fill="currentColor" d="M160.83 416.32c193.2 0 298.92-160.22 298.92-298.92 0-4.51 0-9-.2-13.52A214 214 0 0 0 512 49.38a212.93 212.93 0 0 1-60.44 16.6 105.7 105.7 0 0 0 46.3-58.19 209 209 0 0 1-66.79 25.37 105.09 105.09 0 0 0-181.73 71.91 116.12 116.12 0 0 0 2.66 24c-87.28-4.3-164.73-46.3-216.56-109.82A105.48 105.48 0 0 0 68 159.6a106.27 106.27 0 0 1-47.53-13.11v1.43a105.28 105.28 0 0 0 84.21 103.06 105.67 105.67 0 0 1-47.33 1.84 105.06 105.06 0 0 0 98.14 72.94A210.72 210.72 0 0 1 25 370.84a202.17 202.17 0 0 1-25-1.43 298.85 298.85 0 0 0 160.83 46.92"/></svg>-->
      <!--      <small class="d-md-none ms-2">Twitter</small>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="nav-item col-6 col-md-auto">-->
      <!--    <a class="nav-link p-2" href="https://bootstrap-slack.herokuapp.com/" target="_blank" rel="noopener">-->
      <!--      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" class="navbar-nav-svg d-inline-block align-text-top" viewBox="0 0 512 512" role="img"><title>Slack</title><path fill="currentColor" d="M210.787 234.832l68.31-22.883 22.1 65.977-68.309 22.882z"/><path fill="currentColor" d="M490.54 185.6C437.7 9.59 361.6-31.34 185.6 21.46S-31.3 150.4 21.46 326.4 150.4 543.3 326.4 490.54 543.34 361.6 490.54 185.6zM401.7 299.8l-33.15 11.05 11.46 34.38c4.5 13.92-2.87 29.06-16.78 33.56-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18l-11.46-34.38-68.36 22.92 11.46 34.38c4.5 13.92-2.87 29.06-16.78 33.56-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18l-11.46-34.43-33.15 11.05c-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18c-4.5-13.92 2.87-29.06 16.78-33.56l33.12-11.03-22.1-65.9-33.15 11.05c-2.87.82-6.14 1.64-9 1.23a27.32 27.32 0 0 1-24.56-18c-4.48-13.93 2.89-29.07 16.81-33.58l33.15-11.05-11.46-34.38c-4.5-13.92 2.87-29.06 16.78-33.56s29.06 2.87 33.56 16.78l11.46 34.38 68.36-22.92-11.46-34.38c-4.5-13.92 2.87-29.06 16.78-33.56s29.06 2.87 33.56 16.78l11.47 34.42 33.15-11.05c13.92-4.5 29.06 2.87 33.56 16.78s-2.87 29.06-16.78 33.56L329.7 194.6l22.1 65.9 33.15-11.05c13.92-4.5 29.06 2.87 33.56 16.78s-2.88 29.07-16.81 33.57z"/></svg>-->
      <!--      <small class="d-md-none ms-2">Slack</small>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--  <li class="nav-item col-6 col-md-auto">-->
      <!--    <a class="nav-link p-2" href="https://opencollective.com/bootstrap" target="_blank" rel="noopener">-->
      <!--      <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" fill-rule="evenodd" class="navbar-nav-svg d-inline-block align-text-top" viewBox="0 0 40 41" role="img"><title>Open Collective</title><path fill-opacity=".4" d="M32.8 21c0 2.4-.8 4.9-2 6.9l5.1 5.1c2.5-3.4 4.1-7.6 4.1-12 0-4.6-1.6-8.8-4-12.2L30.7 14c1.2 2 2 4.3 2 7z"/><path d="M20 33.7a12.8 12.8 0 0 1 0-25.6c2.6 0 5 .7 7 2.1L32 5a20 20 0 1 0 .1 31.9l-5-5.2a13 13 0 0 1-7 2z"/></svg>-->
      <!--      <small class="d-md-none ms-2">Open Collective</small>-->
      <!--    </a>-->
      <!--  </li>-->
      <!--</ul>-->

      <!--<a class="btn btn-bd-download d-lg-inline-block my-2 my-md-0 ms-md-3" href="/docs/5.1/getting-started/download/">Download</a>-->
    </div>
  </nav>
</header>


    
  <main>
    
    <header class="py-5 border-bottom text-center" style="user-select: auto;">
    <div class="container pt-md-1 pb-md-4" style="user-select: auto;">
      <div class="row" style="user-select: auto;">
        <div class="col-xl-12" style="user-select: auto;">
          <!--<h1 class="bd-title mt-0" style="user-select: auto;">Tag Manager</h1>-->
          <!--<p class="bd-lead" style="user-select: auto;">This tool help us to generate Udemy course details to HTML tags elements to Blogger posts.</p><br>-->
          
         <form class="needs-validation" novalidate="" style="user-select: auto;" method="POST">
            <div class="col-sm-12">
              <!--<label for="firstName" class="form-label">First name</label>-->
              <input type="text" class="form-control text-center py-4 px-2" id="udemyURL" placeholder="Udemy Course URL" value="" required="" name="udemyURL" onclick="Paste('udemyURL')">
              <input type="text" class="form-control text-center py-2" id="instructorName" placeholder="Instructor Name" value="" required="" name="instructorName">
              <input type="text" class="form-control text-center py-2" id="courseDuration" placeholder="Course Duration" value="" required="" name="courseDuration">
              <select class="form-select text-center py-2" aria-label="Default select example" name="courseType" class="">
                <option value="1" selected>Free</option>
                <option value="2">Premium</option>
              </select>
              <div class="invalid-feedback"></div>
            </div>
            
            <br class="my-4" style="user-select: auto;">

          <button class="btn btn-bd-primary btn-md" type="submit" style="user-select: auto;">Generate Tags</button>
        </form>
          
        </div>
      </div>
    </div>
  </header>
  
  <div class="container col-xl-6 mt-5" style="user-select: auto;">
    
    <label for="courseTitle" class="form-label">Title</label>
    <input type="text" class="form-control" id="courseTitle" placeholder="" value="<?php echo $courseName; ?>" required="" onclick="copyToClipboard('courseTitle')">
    <label for="postLabels" class="form-label mt-3">Posts Labels</label>
    
     <?php if($courseLanguage === 'English') { ?>
        <input type="text" class="form-control" id="postLabels" placeholder="" value="<?php echo $courseCat1.' Courses, '.$courseCat2.' Courses, '.$courseTopic.' Courses, '.$mainInstructorName.' Courses, Udemy'; ?>" onclick="copyToClipboard('postLabels')">
     <?php } else { ?>
       <input type="text" class="form-control" id="postLabels" placeholder="" value="<?php echo $courseCat1.' Courses ('.$courseLanguage.'), '.$courseCat2.' Courses ('.$courseLanguage.'), '.$courseTopic.' Courses ('.$courseLanguage.'), '.$mainInstructorName.' Courses, '.$courseTopic.' Courses ('.$courseLanguage.'), Udemy'; ?>" onclick="copyToClipboard('postLabels')">
     <?php } ?>
     
    <label for="exampleFormControlTextarea1" class="mt-3">Post HTML</label>
    <textarea class="form-control" id="postBody" rows="3" onclick="copyToClipboard('postBody')"><?php echo $output; ?></textarea>
  </div>
  
  <br class="my-7" style="user-select: auto;">
  <br class="my-4" style="user-select: auto;">
  <br class="my-4" style="user-select: auto;">
  <br class="my-4" style="user-select: auto;">

  </main>

  

    <script src="https://getbootstrap.com/docs/5.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>




<script src="https://getbootstrap.com/docs/5.1/assets/js/docs.min.js"></script>

<script>

async function Paste(elementID) {

	if (navigator.clipboard) {

	try {
	// get text FROM the clipboard
       let text = await navigator.clipboard.readText();
       document.getElementById(elementID).value = text;
    } catch (err) {
      alert('Failed to copy:');
    }
  

	} else {

		alert('Clipboard not work on this browser');

	}

}


function copyToClipboard(elementID) {
  /* Get the text field */
  var copyText = document.getElementById(elementID);

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

}
</script>


    
    
  </body>
</html>
