<?php
  
  header("Access-Control-Allow-Origin: *"); //accept cross origin
  include("functions.php"); 
  include("simple_html_dom.php");
  
  if(isset($_POST['courseLink']) || empty($_POST['courseLink'])) {
      
     $courseLink = $_POST['courseLink'];
     $courseLink = trim($_POST['courseLink']);
     $courseLink = str_replace(['https://', 'http://', 'www.'], '', $courseLink);
     $courseLink = 'https://www.' . strtolower($courseLink);
     
     $html = file_get_html($courseLink);
      
     if (strpos($courseLink, 'udemy.com/course/') === false) {
         
         if(!$html) {
             
            echo json_encode(["status" => "error", "body" => "Couldn't get the course data from this link. Try again!"]);

        } else {
             
            echo json_encode(["status" => "error", "body" => "Please enter a valid Udemy course link."]);
         
         }

    } else if(!$html) {
        
        echo json_encode(["status" => "error", "body" => "Couldn't get the course data from this link. Try again!"]);
        
    } else {
       

     $courseId = $html->find('body')[0]->attr["data-clp-course-id"];
     $titleH1 = $html->find('h1',0); // safety check if user want to find a date of a course which is draft or deleted by udemy and no longer accepting enrollments.
     
     if(!$courseId) {  //check is this course page link
        
           echo json_encode(["status" => "error", "body" => "Couldn't get the course data from this link. Try again!"]);

        } else if(!$titleH1) {
            echo json_encode(["status" => "error", "body" => "Couldn't get the course data from this link. Try again!"]);
        }
     

  // // -----------------------------------------------------------------------------------------------

      $json = file_get_contents('https://www.udemy.com/api-2.0/courses/' . $courseId . '/?fields[course]=id,title,headline,created,is_paid,num_subscribers,num_reviews,avg_rating,locale,num_lectures,num_quizzes,price,primary_category,primary_subcategory,visible_instructors,image_240x135');
      $array = json_decode($json);

      $json2 = file_get_contents('https://www.udemy.com/api-2.0/courses/' . $courseId . '/?fields[user]=created');
      $array2 = json_decode($json2);

      $courseId = $array->id;
      $courseName = $array->title;
      $courseImage = $array->image_240x135;
      $studentCount = number_format($array->num_subscribers,0,',',',');
      $reviewsCount = number_format($array->num_reviews,0,',',',');
      $avgRating = $array->avg_rating;
      $courseInstructors = count($array->visible_instructors);
      $instructorRegDate = date('F d, Y', strtotime($array2->visible_instructors[0]->created));
      $courseReleasedDate = date('F d, Y', strtotime($array->created));
      
      
      $last_row_date = strtotime($array->created);
      $last_row_date = humanTiming($last_row_date);
      $courseLink2 = "https://click.linksynergy.com/deeplink?id=FNxb/pO*KcU&mid=39197&murl=" . $courseLink . "?deal_code=2021PM20&couponCode=2021PM20"; //added affiliate link
      
      $output = '<br><center><div><a href="'.$courseLink2.'" rel="nofollow" target="_blank"><img src="'.$courseImage.'" alt="course-image" width="240" height="135"></a></div>';
      $output .= '<h4><a href="'.$courseLink2.'" rel="nofollow" target="_blank">'.$courseName.'</a></h4>';
      $output .= 'Course ID: '.$courseId. '<br>';
      $output .= '~ Course Released On Udemy: <br><h4 style="color:#4faf61;">'.$courseReleasedDate.' <small style="font-weight:normal;">('.$last_row_date.' ago)</small></h4>';
      $output .= '<a href="'.$courseLink2.'" rel="nofollow" target="_blank"><button class="tc-button colored-button" style="background-color: rgb(142, 68, 173);"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Buy This Course Now (Up to 95% Off)</button></a><div style="clear:both;"></div></center>';
      
      echo json_encode(["status" => "success", "body" => $output]);
      exit;
      
   }
  } else {
    
    echo json_encode(["status" => "error", "body" => "Requested method is not allowed."]);
    exit;
    
  }
?>