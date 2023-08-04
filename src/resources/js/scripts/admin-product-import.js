(function ($) {
    $(document).ready(function (event) {
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

})(jQuery);