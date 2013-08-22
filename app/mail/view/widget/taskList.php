
<style>
    
.todo-block {
    position: fixed;
    bottom: 0px;
    right: 15px;
    z-index: 1000;
    width: 200px !important;
}

.todo-block .header {
    cursor: pointer;
}

.todo-block .header h3 {
    margin-bottom: 0;
}

.awe-tasks {
    margin-right: 20px;
}

.todo-block .toggled {
    margin-top: 15px;
}

</style>

<script>
    
$(function() {
    $('.todo-block .toggle').bind('click', function() {
        var display = $('.todo-block .toggled').css('display');
        $('.todo-block .toggled').css('display', display == 'none' ? 'block' : 'none');
    })
})    
    
</script>

<article class="span4 data-block todo-block nested">
    <div class="data-container">
        <div class="toggle header">
            <h3><span class="awe-tasks"></span>Tasks</h3>
        </div>
        <section class="toggled" style="display:none;">
            <form>
                <table class="table">
                    <tbody>
                        <tr class="done">
                            <td><input type="checkbox" value="done" checked=""></td>
                            <td>
                                <p>doItNow(); does not work as expected</p>
                                <span>completed <time>Apr 12</time></span>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" value="done"></td>
                            <td>
                                <p>Folders Menu</p>
                                <span>due <time>Feb 12</time></span>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" value="done"></td>
                            <td>
                                <p><span class="label label-important">Important</span> meeting at 10:30 am</p>
                                <span>due <time>Feb 15</time></span>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" value="done"></td>
                            <td>
                                <p>take out the trash</p>
                                <span>due <time>Apr 17</time></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </section>
    </div>
</article>