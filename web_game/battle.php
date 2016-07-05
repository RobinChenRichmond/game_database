<!DOCTYPE html>
<html>
<head>
	<title>Guanyu's garden</title>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="cookieFunctions.js"></script>
	<script type="text/javascript" src="gameFunctions.js"></script>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="basic.css" rel="stylesheet"/>
</head>

<body>
<p id = "temp"><p>
<p id = "intro"></p>
<p id = "intro2"></p>
<br>
<p>Your Info:</p>
<table id = "charTable" style="width:30%">
<tbody>
</tbody>
</table>
<br>
<p>Enemy Info:</p>
<table id = "enemyTable" style="width:30%">
<tbody>
</tbody>
</table>
<br>
<p>Your Skills:</p>
<table id = "skillTable" style="width:30%">
<tbody>
</tbody>
</table>
<p id = "forTest"></p>
<p id = "enemyAct"></p>
<p id = "getWeapon"></p>

<script>

// Character Attributes
var char = getCookie("charName");
var charId = getCookie("charId");
var charHp = parseInt(getCookie("charHp"));
var charMhp = parseInt(getCookie("charMhp"));
var charMp = parseInt(getCookie("charMp"));
var charMmp = parseInt(getCookie("charMmp"));
var charDef = parseInt(getCookie("charDef"));
var charExp = parseInt(getCookie("charExp"));
var lv = parseInt(getCookie("level"));
var charProf = getCookie("charProf");
var charW = getCookie("charW");
var charA = getCookie("charA");


document.cookie = "charName=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charId=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charHp=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charMp=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charMhp=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charMmp=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charDef=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charExp=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "level=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charProf=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charW=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
document.cookie = "charA=; expires=Thu, 01 Jan 1970 00:00:00 UTC";

// fields
var charWeaponInfo;
var charArmorInfo;
var enemyWeaponInfo;
var enemyArmorInfo;
var enemyInfo;
var yourSkills;
var enemySkills;

var enemyName;
var enemyHp;
var enemyMp;
var enemyDef;
var enemyLv;
var enemyProf;
var enemyW;
var enemyA;

var charDmg;
var charAcc;
var charTyp;
var charAD;

var enemyDmg;
var enemyAcc;
var enemyTyp;
var enemyAD;

var numOfESkills;
var numOfSkills;

// functions needed to be improved

function saveCharInfo(char,charId,charHp,charMhp,charMp,charMmp,charDef,charExp,lv,charW,charA){
	$.ajax({                                      
	      url: 'save.php',                  //the script to call to get data      
	      data: {'cN':char,'cI':charId,'cH':charHp,'cMh':charMhp,'cM':charMp,'cMm':charMmp,'cD':charDef,'cE':charExp,'cL':lv,'cW':charW,'cA':charA}, 
	      success: function(data)          //on recieve of reply
	      {	     		
	      }
	});
}

// Need to modify
function backOrContinue(){
    if (confirm("Are you going to find another enemy?") == true) {
    	$("#skillTable tr").remove();
	runGame();
    } else {
    	document.cookie = "charId = " + charId;
    	location = "newgame.php";
    }
}

function win(){
	alert("YOU WIN!!");
	if(lv == 50){
		document.getElementById("enemyAct").innerHTML = "You have reached the highest level and cannot gain more exp!!!";
	} else {
		var expBonus = 0;
		if (enemyLv > lv) {
	                expBonus = (enemyLv - lv) * enemyLv + 10;
	        }
	        //expBonus = 9999;	//used when want to test the levelUp function
	        var expUp = parseInt(Math.pow(1.1, lv) + 50 + expBonus);
	        charExp = charExp + expUp;
	        var levelNeeded = parseInt(20 * Math.pow(1.1, lv) + 100);
	        if(charExp >= levelNeeded){
	        	document.getElementById("enemyAct").innerHTML = "LEVEL UP!! You are now LV " + (lv+1) + "!! You are also fully cured.";
	        	levelUp();
	        } else{
	        	document.getElementById("enemyAct").innerHTML = "You gain " + expUp + " exp, current exp: " + charExp + "/" + levelNeeded + " You also recovered 10% health and mana.";
	        	if(charHp < charMhp*0.9){
	        		charHp = charHp + parseInt(charMhp*0.1);
	        	} else {
	        		charHp = charMhp;
	        	}
	        	
	        	if(charMp < charMmp*0.9){
	        		charMp = charMp + parseInt(charMmp*0.1);
	        	} else {
	        		charMp = charMmp;
	        	}
	        }
	}
	saveCharInfo(char,charId,charHp,charMhp,charMp,charMmp,charDef,charExp,lv,charW,charA);
	backOrContinue();
}

function lose(){
	alert("YOU LOSE!!");
	charHp = charMhp;
	charMp = charMmp;
	saveCharInfo(char,charId,charHp,charMhp,charMp,charMmp,charDef,charExp,lv,charW,charA);
	backOrContinue();
}

function levelUp(){
	var temp;
	lv = lv + 1;
	charExp = 0;
	if(charProf == "Archer"){
		charMhp = charMhp+20;
		charMmp = charMmp+3;
		charDef = charDef+2;
	} else if(charProf == "Fighter"){
		charMhp = charMhp+24;
		charMmp = charMmp+2;
		charDef = charDef+4;
	} else if(charProf == "Thief"){
		charMhp = charMhp+17;
		charMmp = charMmp+4;
		charDef = charDef+1;
	} else{
		charMhp = charMhp+15;
		charMmp = charMmp+6;
	} 
	charHp = charMhp;
	charMp = charMmp;
	
	if(lv%10 == 0){
		fetchWeaponName(charProf, lv, function(returnedData){
		    charW = String(returnedData);
		});
		document.getElementById("getWeapon").innerHTML = "You get new weapon: " + charW + "!!!";
	}
}



function useSkill(skill){
	var dmg;
	var type;
	var mana_req;
	for(var i = 0; i < numOfSkills; i ++){
		if(yourSkills[4*i] == skill){
			dmg = yourSkills[4*i+1];
			mana_req = yourSkills[4*i+2];
			type = yourSkills[4*i+3];
		}
	}
	if(parseInt(mana_req) > parseInt(charMp)){
		document.getElementById("forTest").innerHTML = "No enough Mana!!";
	} else{
		var randomHit = Math.random();
		if(randomHit > charAcc){
			document.getElementById("forTest").innerHTML = "You used the skill: <strong>" + skill + "</strong>, but your attack missed...";
			enemyMove();
		} else{
			var finalTrueDamage = (parseInt(charDmg) + parseInt(dmg))/2;
			var finalDamage = parseInt(finalTrueDamage*100/(100+enemyDef+enemyAD));
			document.getElementById("forTest").innerHTML = "You used the skill: <strong>" + skill + "</strong>, damage: (" + charDmg + "+" + dmg + ")/2=" + finalDamage + " enemyA: " + enemyDef + " + " + enemyAD;
			enemyHp = parseInt(enemyHp)-finalDamage;
			document.getElementById("ehp").innerHTML = enemyHp;
			if((enemyHp) <= 0){
				battleEnd = true;
				win();
			} else{
				enemyMove();
			}
		}
		charMp = charMp-mana_req;
		document.getElementById("cmp").innerHTML = charMp + "/" + charMmp;
	}
}

function enemyMove(){
		// Not an efficient way. Better way: find available skills first.
		var enemyLuckyNum = Math.floor(Math.random()*numOfESkills);
		var mana_req = parseInt(enemySkills[4*enemyLuckyNum+2]);

		while(mana_req > enemyMp){
			enemyLuckyNum = Math.floor(Maths.random()*numOfESkills);
			mana_req = parseInt(enemySkills[4*enemyLuckyNum+2]);
		}
		
		var dmg = enemySkills[4*enemyLuckyNum+1];
		var type = enemySkills[4*enemyLuckyNum+3];
		var eRandomHit = Math.random();
		
		
		if(eRandomHit > enemyAcc){
			document.getElementById("enemyAct").innerHTML = "Enemy used the skill: <strong>" + enemySkills[4*enemyLuckyNum] + "</strong>, and enemy's attack missed...";
		} else{
			var finalTrueDamage = (parseInt(enemyDmg) + parseInt(dmg))/2;
			var finalDamage = parseInt(finalTrueDamage*100/(100+charDef+charAD));
			document.getElementById("enemyAct").innerHTML = "Enemy used the skill: <strong>" + enemySkills[4*enemyLuckyNum] + "</strong>, damage: (" + enemyDmg + "+" + dmg + ")/2=" + finalDamage;
			charHp = charHp-finalDamage;
			document.getElementById("chp").innerHTML = charHp + "/" + charMhp;
			if(charHp <= 0){
				battleEnd = true;
				lose();
			}
		}
		enemyMp = enemyMp-mana_req;
		document.getElementById("emp").innerHTML = enemyMp;
}


// RUN GAME
function runGame(){
	getEnemy(function(returnedData){ //anonymous callback function
	    enemyInfo = returnedData;
	});
	
	// Attributes of Enemy
	enemyName = enemyInfo[0];
	enemyHp = parseInt(enemyInfo[1]);
	enemyMp = parseInt(enemyInfo[2]);
	enemyDef = parseInt(enemyInfo[3]);
	enemyLv = parseInt(enemyInfo[4]);
	enemyProf = enemyInfo[5];
	enemyW = enemyInfo[6];
	enemyA = enemyInfo[7];
	
	fetchWeaponInfo(charW,function(returnedData){ //anonymous callback function
	    charWeaponInfo = returnedData;
	});
	
	charDmg = parseInt(charWeaponInfo[1]);
	charAcc = parseFloat(charWeaponInfo[3]);
	charTyp = charWeaponInfo[4];
	
	fetchArmorInfo(charA,function(returnedData){ //anonymous callback function
	    charArmorInfo = returnedData;
	});
	
	charAD = parseInt(charArmorInfo[1]);
	
	fetchWeaponInfo(enemyW,function(returnedData){ //anonymous callback function
	    enemyWeaponInfo = returnedData;
	});

	enemyDmg = parseInt(enemyWeaponInfo[1]);
	enemyAcc = parseFloat(enemyWeaponInfo[3]);
	enemyTyp = enemyWeaponInfo[4];
	
	fetchArmorInfo(enemyA,function(returnedData){ //anonymous callback function
	    enemyArmorInfo = returnedData;
	});
	
	enemyAD = parseInt(enemyArmorInfo[1]);
	
	availableSkills(charProf, lv, function(returnedData){ //anonymous callback function
	    yourSkills = returnedData;
	});
	
	availableSkills(enemyProf, enemyLv, function(returnedData){
	    enemySkills = returnedData;
	});
	
	numOfESkills = enemySkills.length/4;
	numOfSkills = yourSkills.length/4;
	
	document.getElementById("intro").innerHTML = "You are " + char + " the " + charProf + " and your lv is " + lv + "<br>";
	document.getElementById("intro2").innerHTML = "Enemy found!!";

	$('#charTable > tbody').html("<tr><td>Your Character</td><td>hp</td><td>mp</td><td>lvl</td><td>profession</td></tr><tr><td>" + char + "</td><td id = 'chp'>" + charHp + "/" + charMhp + "</td><td id = 'cmp'>" + charMp + "/" + charMmp + "</td><td>" + lv + "</td><td>" + charProf + "</td></tr>");
	
	$('#skillTable > tbody').append("<tr><td>Name</td><td>Damage</td><td>Mana cost</td><td>Type</td></tr>");
	for(var i=0;i<numOfSkills;i++){
		var word = "<tr><td><button input type='button' onclick = 'useSkill(yourSkills[4*" + i + "])'>" +yourSkills[4*i] + "</button></td><td>" + yourSkills[4*i+1] + "</td><td>" + yourSkills[4*i+2] + "</td><td>" + yourSkills[4*i+3] + "</td></tr>";
		$('#skillTable > tbody').append(word);
	}
}

runGame();


</script>


<br>
<button input type = "button" onclick = "location = 'login.html'">You must click this to go back</button>
<button input type = "button" onclick = "getEnemy()">generate again</button>
</body>
</html>