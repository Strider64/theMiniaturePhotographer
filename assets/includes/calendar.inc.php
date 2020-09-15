<section class="calendar-box">
    <div class="monthlyHeader">
        <?Php
        foreach ($calendar as $inner) {
            foreach ($inner as $key => $item) {
                if ($key === 'previous') {

                    echo '<a data-pos="prev" class="control prev-left" href="' . $item . '"><i class="fas fa-chevron-left"></i></a>';
                }
                if ($key === 'month') {

                    echo '<h1 class="control displayMonth">' . $item . '</h1>';
                }
                if ($key === 'next') {

                    echo '<a data-pos="next" class="control next-right" href="' . $item . '"><i class="fas fa-chevron-right"></i></a>';
                }
            } // End Inner Foreach
        } // End Outer Foreach 
        ?>                    
    </div>
    <div class="daysOfTheWeek">
        <?php
        echo '<p class="day">sun</p>';
        echo '<p class="day">mon</p>';
        echo '<p class="day">tue</p>';
        echo '<p class="day">wed</p>';
        echo '<p class="day">thu</p>';
        echo '<p class="day">fri</p>';
        echo '<p class="day">sat</p>';
        ?>
    </div>

    <div class="displayCurrentMonth">
        <?php
        foreach ($calendar as $inner) {
            foreach ($inner as $key => $item) {
                if ($key === 'class') {
                    echo '<p class="' . $item . '">';
                }
                if ($key === 'date') {
                    echo $item;
                    echo '</p>';
                }
            } // End Inner Foreach
        } // End Outer Foreach
        ?>
    </div>
</section>