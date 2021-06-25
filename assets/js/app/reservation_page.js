import $ from 'jquery';


$(document).ready(function() {
    // Get the ul that holds the collection of tags
    //var $booksCollectionHolder = $('ul.books');
    var $booksCollectionHolder = $('div.js-booklist-wrapper');

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $booksCollectionHolder.data('index', $booksCollectionHolder.find('input').length);

    $('body').on('click', '.js-add-book-item', function(e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        // add a new book form (see next code block)
        addFormToCollection($collectionHolderClass);
    })
});

function addFormToCollection($collectionHolderClass) {
    // Get the div that holds the collection of tags
    var $collectionHolder = $('.' + $collectionHolderClass);

    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your books field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormDiv = $('<div></div>').append(newForm);
    // Add the new form at the end of the list
    $collectionHolder.append($newFormDiv)
}

$(document).ready(function () {
    var $wrapper = $('.js-booklist-wrapper');

    $wrapper.on('click', '.js-remove-book-item', function (e) {
        e.preventDefault();

        $(this).closest('.js-book-item').remove();
    });
});