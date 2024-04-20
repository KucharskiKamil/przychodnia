jQuery(document).ready(function($) 
{
    $(document).ready(function() 
    {
        $('#pacjentSearchBar').keyup(function() 
        {
            var searchQuery = $(this).val();
            if (searchQuery !== '') 
            {
                $.ajax({
                url: '../php/pacjentSearch.php',
                type: 'POST',
                data: { query: searchQuery },
                success: function(response)
                {
                    $('#wynikiSearchBar').html(response);
                }
                });
            } 
            else 
            {
                $('#wynikiSearchBar').empty();
            }
        });
  });
});