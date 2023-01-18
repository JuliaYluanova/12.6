<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

//Разбиение и объединение ФИО
function getPartsFromFullname ($fullName) {
    $person_name = ['surname', 'name', 'patronomyc'];
    return array_combine($person_name,explode(' ', $fullName));
}
echo 'Результат разбиения ФИО на части:' . "<br>";
$arrParts = getPartsFromFullname($example_persons_array[4]['fullname']);
print_r($arrParts);
echo "<br>"."<br>";


function getFullnameFromParts ($surname, $name, $patronomyc) {
    return $surname .= ' ' . $name . ' ' . $patronomyc;
}
echo 'Результат объединения ФИО:' . "<br>";
$arrFullName = getFullnameFromParts($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc']);
print_r($arrFullName);
echo "<br>"."<br>";

//Сокращение ФИО
function getShortName ($fullName) {
    $socrFIO = getPartsFromFullname ($fullName);
    return $socrFIO['name']. ' ' .mb_substr($socrFIO['surname'], 0, 1). '.';
}
echo 'Результат для сокращения ФИО:' . "<br>";
$arrSocr = getShortName($example_persons_array[4]['fullname']);
print_r($arrSocr);
echo "<br>"."<br>";

//Функция определения пола по ФИО
function getGenderFromName ($fullName) {
    $socrFIO = getPartsFromFullname ($fullName);
    $gender = 0;
    //Женский пол:
    if (mb_substr($socrFIO['surname'], -2, 2) == 'ва') {
        --$gender;
    }
    if (mb_substr($socrFIO['name'], -1, 1) == 'О') {
        --$gender;
    }
    if (mb_substr($socrFIO['name'], -1, 1) == 'ая') {
        --$gender;
    }
    if (mb_substr($socrFIO['patronomyc'], -3, 3) == 'вна') {
        --$gender;
    }
    //Мужской пол:
    if (mb_substr($socrFIO['surname'], -1, 1) == 'в') {
        ++$gender;
    }
    if (mb_substr($socrFIO['name'], -1, 1) == 'й') {
        ++$gender;
    }
    if (mb_substr($socrFIO['patronomyc'], -2, 2) == 'ич') {
        ++$gender;
    } 
    if (mb_substr($socrFIO['name'], -1, 1) == 'О') {
        ++$gender;
    }
    if (mb_substr($socrFIO['name'], -3, 3) == 'ер') {
        ++$gender;
    }
    
    switch($gender <=> 0){
        case 1:
            return 'Мужчина';
            break;
        case -1:
            return 'Женщина';
            break;
        default:
            return'Не удалось определить';
    }   
}
for ($i=0;$i<count($example_persons_array);$i++){
    // Определение возрастно-полового состава
$arrGender[$example_persons_array[$i]['fullname']] = getGenderFromName($example_persons_array[$i]['fullname']);
}
echo 'Результат определения пола по ФИО' . "<br>";
print_r($arrGender);
echo "<br>"."<br>";

function getGenderDescription($array){
    
    $males = array_filter($array, function($person) {
        return getGenderFromName($person['fullname']) == 'Мужчина';
    });

    $females = array_filter($array, function($person) {
        return getGenderFromName($person['fullname']) == 'Женщина';
    });

    $und = array_filter($array, function($person) {
        return getGenderFromName($person['fullname']) == 'Не удалось определить';
    });

    $malePercent = round(count($males)*100/count($array), 1);
    $femalePercent = round(count($females)*100/count($array), 1);
    $unknonwPercent = round(count($und)*100/count($array), 1);
    
    echo <<<HEREDOCLETTER
    Гендерный состав аудитории:<br>
    ---------------------------<br>
    Мужчины - $malePercent%<br>
    Женщины - $femalePercent%<br>
    Не удалось определить - $unknonwPercent%<br>
    HEREDOCLETTER;
    echo "<br>"."<br>";
}
getGenderDescription($example_persons_array);

//Идеальный подбор пары
function getPerfectPartner($surname, $name, $patronomyc, $partners_array) {
    $surname1 = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name1 = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronomyc1 = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);  
    $personFullName = getFullnameFromParts($surname1, $name1, $patronomyc1);  
    $genderPerson = getGenderFromName($personFullName);
    while ($genderPerson === 0){        
        return 1;
    }
    $randomPartner = $partners_array[random_int(0, count($partners_array)-1)]['fullname']; 
    $genderPartner = getGenderFromName($randomPartner);
    $shortPersonName = getShortName($personFullName);
    $shortPartnerName = getShortName($randomPartner);
    $percentCompatibility = mt_rand(50, 100) + mt_rand(0, 100)/100;

    echo 'Идеального подбор пары' . "<br>";
    echo $shortPersonName . " + " . $shortPartnerName . " = " . "<br>";
    echo "♡". " Идеально на ". $percentCompatibility. "% " ."♡";
    return 0;
}
$arrParts = getPartsFromFullname($example_persons_array[random_int(0, count($example_persons_array)-1)]['fullname']);
$choosePartner = getPerfectPartner($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc'], $example_persons_array);

 ?>
