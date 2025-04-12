
const  addCourseForm= document.getElementById("addCourseForm");
const addCourse = document.getElementById("addCourse");
const overlay = document.querySelector('#overlay');
const addyearlevel=document.getElementById('addyearlevel');
const addyearlevelForm=document.getElementById("addyearlevelForm");



addCourse.addEventListener("click", function() {
  addCourseForm.style.display = "block";
  overlay.style.display="block";
  document.body.style.overflow = 'hidden'; 


});

  addyearlevel.addEventListener("click", function() {
    addyearlevelForm.style.display = "block";
    overlay.style.display="block";
    document.body.style.overflow = 'hidden'; 
  
  
  });


  var closeButtons = document.querySelectorAll('#addCourseForm .close, #addyearlevelForm .close');

  closeButtons.forEach(function(closeButton) {
      closeButton.addEventListener('click', function() {
          addCourseForm.style.display = "none";
          addyearlevelForm.style.display="none";
          overlay.style.display = 'none';
          document.body.style.overflow = 'auto'; 
      });
  });
