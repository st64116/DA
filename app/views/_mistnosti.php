<?php
include_once('database/Client.php');
$db = new Client();
?>
<table class="mistnosti ms-auto me-auto text-center shadow-lg mt-3">

    <div class="text-start my-2 filter p-2">
        <button class="btn btn-primary text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter" aria-expanded="false" aria-controls="filter">Filtr
        </button>
        <div class="collapse" id="filter">
            <div class=" mt-3 px-2 py-3 p-sm-5 border border-dark rounded-3">
                <div>
                    <label>Patro:</label>
                    <select name="patra" id="patra">
                        <option value="nevybrano"></option>
                        <?php
                        foreach($db->view_patra() as $patro){
                            echo "<option value='" .$patro["NAZEV"] ."'>".$patro["NAZEV"]."</option>";
                        }

                        ?>
                    </select>
                    <datalist id="browsers">

                    </datalist>

                </div>
                <div>

                </div>
                <div class="row">
                    <div class="mt-3 text-start col-6">
                        <button class="btn btn-lg btn-danger text-uppercase" onclick="clear()">clear
                        </button>
                    </div>
                    <div class="mt-3 text-end col-6">
                        <button class="btn btn-lg btn-danger text-uppercase" onclick="search()">search
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <thead class="shadow">
        <tr class="text-uppercase">
            <th scope="col">nazev</th>
            <th scope="col">účel</th>
            <th scope="col">umísťění</th>
            <th scope="col">patro</th>
            <th scope="col">velikost</th>
        </tr>
    </thead>
    <tbody>
    <?php
//    echo "<script>console.log(" . $db->view_mistnosti()[0]["ID_MISTNOSTI"] . ");</script>";
    foreach ($db->view_mistnosti() as $mistnost) {
        echo '<tr scope="row">';
            echo "<td>" ."<span class='my-4'>". $mistnost["NAZEV_MISTNOSTI"] ."</span>" ."</td>";
            echo "<td>" . $mistnost["NAZEV_UCELU"]  ."</td>";
            echo "<td>" . $mistnost["NAZEV_UMISTENI"]  ."</td>";
            echo "<td>" . $mistnost["NAZEV_PATRA"]  ."</td>";
            echo "<td>" . $mistnost["ANZEV_VELIKOSTI"]  ."</td>";
//        foreach ($mistnost as $key => $value){
//            echo "$key => $value";
//            echo "<br>";
//        }
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
<style>

    .filter{

    }

    .mistnosti{
        width: 100%;
    }
    .mistnosti tr{
        padding-top: 10px;
        margin: 10px;
    }

    .mistnosti th{
        background-color: var(--color1);
        padding: 16px 8px 16px 8px;
        color: white;
    }

    .mistnosti td{
        padding: 8px;
        font-weight: bold;
    }
    .mistnosti tr:hover{
        background-color: var(--color2);
        color: white;
    }
    
    @media(max-width: 767px){
        .mistnosti th{
            padding: 8px 2px 8px 2px;
            font-size: 0.7em;
        }

        .mistnosti td{
            padding: 8px;
            font-size: 0.7em;
        }
    }
</style>