
const overlay = document.querySelector('#overlay');
const addUnit=document.getElementById('addUnit');

addUnit.addEventListener("click", function() {
    addUnitForm.style.display = "block";
    overlay.style.display="block";
    document.body.style.overflow = 'hidden'; 
  
  
  });

  var closeButtons = document.querySelectorAll('#addUnitForm .close');

  closeButtons.forEach(function(closeButton) {
      closeButton.addEventListener('click', function() {
          addUnitForm.style.display = "none";
          overlay.style.display = 'none';
          document.body.style.overflow = 'auto'; 
      });
  });
