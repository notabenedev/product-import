(function ($) {
    $(document).ready(function (event) {
        setFileName();

        let categoriesElement = document.getElementById('xmlCategoryParentType');
        let variationsElement = document.getElementById('xmlVariationType');

        if(categoriesElement) {
            showHide(categoriesElement,"show-attribute","show-element-tree",'xmlCategoryParentAttributeBlock','xmlCategoryElementTreeBlock' );
            categoriesElement.addEventListener('change', function(){
                showHide(categoriesElement,"show-attribute","show-element-tree",'xmlCategoryParentAttributeBlock','xmlCategoryElementTreeBlock' );
            });
        }
        if(variationsElement) {
            showHide(variationsElement,"show-product","show-file",'xmlVariationProductBlock','xmlVariationFileBlock' );


            variationsElement.addEventListener('change', function(){
                showHide(variationsElement,"show-product","show-file",'xmlVariationProductBlock','xmlVariationFileBlock' );
            });
        }

    });

    function showHide(el, firstClass, secondClass, firstBlockId, secondBlockId){
        let isFirst = el.options[el.selectedIndex].classList.contains(firstClass);
        let isSecond = el.options[el.selectedIndex].classList.contains(secondClass);
        let firstBlock =  document.getElementById(firstBlockId);
        let secondBlock =  document.getElementById(secondBlockId);
        firstBlock.style.display = isFirst ? "block" : "none";
        secondBlock.style.display = isSecond ? "block" : "none";
    }

    function setFileName(){

        let fileName = document.getElementById('fileName');
        let fileInput = document.getElementById('fileInput');
        let fileSubmit = document.getElementById('fileSubmit');
        if (fileInput && fileName){
           fileInput.onchange = function () {
                if (this.files[0]) // если выбрали файл
                {
                    fileName.value = this.files[0].name;
                    fileSubmit.hidden = false;
                }
            };
        }
    }


})(jQuery);