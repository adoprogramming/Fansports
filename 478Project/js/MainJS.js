window.onload = init;
/*
 File Name: MainJS.js
 
 Version 1.0.2
 CSC 478 Group Project
 Group: FanSports
 Wesley Elliot, Jeremy Jones, Ann Oesterle
 Last Updated: 11/29/2016
 */
//*****global*****
'use strict';

//*****Functions*****
function init() {
  U.$('posSelectorButton').onclick = function () {
    if (U.$('posSelectorMenu').value === "QB") {
      document.getElementById("draftMenuQB").style.display = "inline-block";
      document.getElementById("buttonQB").style.display = "inline-block";
    }
    if (U.$('posSelectorMenu').value === "WR") {
      document.getElementById("draftMenuWR").style.display = "inline-block";
      document.getElementById("buttonWR").style.display = "inline-block";
    }
    if (U.$('posSelectorMenu').value === "RB") {
      document.getElementById("draftMenuRB").style.display = "inline-block";
      document.getElementById("buttonRB").style.display = "inline-block";
    }
    if (U.$('posSelectorMenu').value === "TE") {
      document.getElementById("draftMenuTE").style.display = "inline-block";
      document.getElementById("buttonTE").style.display = "inline-block";
    }
    if (U.$('posSelectorMenu').value === "K") {
      document.getElementById("draftMenuK").style.display = "inline-block";
      document.getElementById("buttonK").style.display = "inline-block";
    }
    if (U.$('posSelectorMenu').value === "DEF") {
      document.getElementById("draftMenuDEF").style.display = "inline-block";
      document.getElementById("buttonDEF").style.display = "inline-block";
    }
  };
  U.$('buttonQB').onclick = function () {
    document.getElementById("posSelectorMenu").style.display = "none";
    document.getElementById("posSelectorButton").style.display = "none";
    document.getElementById("infoThatChanges1").style.display = "none";
    document.getElementById("infoThatChanges2").style.display = "inline-block";
  };
   U.$('buttonWR').onclick = function () {
    document.getElementById("posSelectorMenu").style.display = "none";
    document.getElementById("posSelectorButton").style.display = "none";
   document.getElementById("infoThatChanges1").style.display = "none";
    document.getElementById("infoThatChanges2").style.display = "inline-block";
  };
   U.$('buttonRB').onclick = function () {
    document.getElementById("posSelectorMenu").style.display = "none";
    document.getElementById("posSelectorButton").style.display = "none";
   document.getElementById("infoThatChanges1").style.display = "none";
    document.getElementById("infoThatChanges2").style.display = "inline-block";
  };
  U.$('buttonTE').onclick = function () {
    document.getElementById("posSelectorMenu").style.display = "none";
    document.getElementById("posSelectorButton").style.display = "none";
    document.getElementById("infoThatChanges1").style.display = "none";
    document.getElementById("infoThatChanges2").style.display = "inline-block";
  };
  U.$('buttonK').onclick = function () {
    document.getElementById("posSelectorMenu").style.display = "none";
    document.getElementById("posSelectorButton").style.display = "none";
    document.getElementById("infoThatChanges1").style.display = "none";
    document.getElementById("infoThatChanges2").style.display = "inline-block";
  };
  U.$('buttonDEF').onclick = function () {
    document.getElementById("posSelectorMenu").style.display = "none";
    document.getElementById("posSelectorButton").style.display = "none";
    document.getElementById("infoThatChanges1").style.display = "none";
    document.getElementById("infoThatChanges2").style.display = "inline-block";
  };
  U.$('draftForm').onsubmit=function(){
    document.getElementById("posSelectorMenu").style.display = "none";
    document.getElementById("posSelectorButton").style.display = "none";
    document.getElementById("infoThatChanges1").style.display = "none";
    document.getElementById("infoThatChanges2").style.display = "inline-block";
  };
}
