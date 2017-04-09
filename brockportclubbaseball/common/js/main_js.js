/**
 * Created by Justin on 7/19/14.
 */
$(".plate_box").mouseup(function() {
    $( this ).addClass("plate_shadow");
}).mousedown(function() {
    $( this ).removeClass("plate_shadow");
});

$('#gamesList li').click(function() {
    var link = $(this).find('a');
    window.location.href = link.attr("href");
});

$( ".player_pop_up_item" )
    .mouseenter(function() {
        $( this).css({'background-color' : '#d9d9d9'})
    })
    .mouseleave(function() {
        $( this).css({'background-color' : ''})
    });

$( ".player_pop_up_item input" ).click(function(e) {
    e.stopPropagation();
});

$( ".player_pop_up_item" ).click(function() {
     var checkbox = $(this).find('input');
    if(checkbox.is(":checked")) {
        checkbox.removeAttr("checked");
    } else {
        checkbox.attr("checked", "checked");
    }
});

$( "#pitchersImageHeader" ).click(function() {
  $('#lightbox').show();
});

$( "#hittersImageHeader" ).click(function() {
    $('#lightbox').show();
});

(function($) {
    var oldHTML = $.fn.html;

    $.fn.formhtml = function() {
        if (arguments.length) return oldHTML.apply(this,arguments);
        $("input,button", this).each(function() {
            this.setAttribute('value',this.value);
        });
        $("textarea", this).each(function() {
            // updated - thanks Raja & Dr. Fred!
            $(this).text(this.value);
        });
        $("input:radio,input:checkbox", this).each(function() {
            // im not really even sure you need to do this for "checked"
            // but what the heck, better safe than sorry
            if (this.checked) this.setAttribute('checked', 'checked');
            else this.removeAttribute('checked');
        });
        $("option", this).each(function() {
            // also not sure, but, better safe...
            if (this.selected) this.setAttribute('selected', 'selected');
            else this.removeAttribute('selected');
        });
        return oldHTML.apply(this);
    };

    //optional to override real .html() if you want
    // $.fn.html = $.fn.formhtml;
})(jQuery);

$( "#addPitchersPopUpButton" ).click(function() {
    if($('#noPitchersMessage').length > 0) {// no Pitchers added yet
        // first need to get a list of all the elements selected - need the name and the playerId
        var selectedPlayers = [];
        $( ".player_pop_up_item" ).each(function() {
            var checkbox = $( this ).find('input');
            if(checkbox.is(":checked")) {
                var selectedPlayer = {};
                selectedPlayer["playerId"] = checkbox.val();
                selectedPlayer["name"] = $( this ).find('span').html();
                selectedPlayers.push(selectedPlayer);
            }
        });

        // now we no that no pitchers were added, so each of these should go in the table
        var bodyContent = "";
        for (var i = 0; i < selectedPlayers.length; ++i) {
            bodyContent += '<tr id="' + selectedPlayers[i].playerId + '-Stats">' +
                '<td class="firstCell"><input type="hidden" name="hiddenIdentifier[]" value="' + selectedPlayers[i].playerId + '">' + selectedPlayers[i].name + '</td>' +
                '<td><input type="text" name="w-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="l-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="g-' + selectedPlayers[i].playerId + '" class="stat_table_input" value="1"></td>' +
                '<td><input type="text" name="gs-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="cg-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="sv-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="svo-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="ip-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="h-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="r-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="er-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="hr-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="bb-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="so-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="sho-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="hbp-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '</tr>';
        }

        // need to add the form and the table
        var htmlContent = '<form method="post" id="addPitchersForm">' +
            '<table class="bcb_table">' +
            '<thead>' +
                '<tr class="firstRow">' +
                    '<th class="firstCell">Name</th>' +
                    '<th>W</th>' +
                    '<th>L</th>' +
                    '<th>G</th>' +
                    '<th>GS</th>' +
                    '<th>CG</th>' +
                    '<th>SV</th>' +
                    '<th>SVO</th>' +
                    '<th>IP</th>' +
                    '<th>H</th>' +
                    '<th>R</th>' +
                    '<th>ER</th>' +
                    '<th>HR</th>' +
                    '<th>BB</th>' +
                    '<th>SO</th>' +
                    '<th>SHO</th>' +
                    '<th>HBP</th>' +
                '</tr>' +
            '</thead>' +
            '<tbody id="pitchersTableBody">' +
            bodyContent +
            '</tbody>' +
            '</table>' +

            '<input type="hidden" name="addPitchersSubmitForm">' +
            '<button type="submit"  id="submitAddPitchersButton" class="fancyButton fancyButton-hvr">Save</button>';

        $('#pitchersBoxElement').html(htmlContent);
    } else {
        // add to the body element

        // first need to get a list of all the elements even if not selected - if not selected and it's already in table we should remove it
        var allPlayers = [];
        $( ".player_pop_up_item" ).each(function() {
            var checkbox = $( this ).find('input');
            var player = {};
            player["playerId"] = checkbox.val();
            player["name"] = $( this ).find('span').html();
            if(checkbox.is(":checked")) {
                player['isSelected'] = true;
            } else {
                player['isSelected'] = false;
            }
            allPlayers.push(player);
        });

        var newRowsContent = "";
        for (var j = 0; j < allPlayers.length; ++j) {
            if($('#' + allPlayers[j].playerId + '-Stats').length > 0) {// row for this player already exists
                if(allPlayers[j].isSelected == false) {
                    $('#' + allPlayers[j].playerId + '-Stats').remove();
                }
            } else {
                if(allPlayers[j].isSelected == true) {
                    newRowsContent += '<tr id="' + allPlayers[j].playerId + '-Stats">' +
                        '<td class="firstCell"><input type="hidden" name="hiddenIdentifier[]" value="' + allPlayers[j].playerId + '">' + allPlayers[j].name + '</td>' +
                        '<td><input type="text" name="w-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="l-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="g-' + allPlayers[j].playerId + '" class="stat_table_input" value="1"></td>' +
                        '<td><input type="text" name="gs-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="cg-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="sv-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="svo-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="ip-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="h-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="r-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="er-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="hr-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="bb-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="so-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="sho-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="hbp-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '</tr>';
                }
            }
        }

        $('#pitchersTableBody').html($('#pitchersTableBody').formhtml() + newRowsContent);
    }
    $('#lightbox').hide();
});

$( "#addHittersPopUpButton" ).click(function() {
    if($('#noHittersMessage').length > 0) {// no Hitters added yet
        // first need to get a list of all the elements selected - need the name and the playerId
        var selectedPlayers = [];
        $( ".player_pop_up_item" ).each(function() {
            var checkbox = $( this ).find('input');
            if(checkbox.is(":checked")) {
                var selectedPlayer = {};
                selectedPlayer["playerId"] = checkbox.val();
                selectedPlayer["name"] = $( this ).find('span').html();
                selectedPlayers.push(selectedPlayer);
            }
        });

        // now we no that no hitters were added, so each of these should go in the table
        var bodyContent = "";
        for (var i = 0; i < selectedPlayers.length; ++i) {
            bodyContent += '<tr id="' + selectedPlayers[i].playerId + '-Stats">' +
                '<td class="firstCell"><input type="hidden" name="hiddenIdentifier[]" value="' + selectedPlayers[i].playerId + '">' + selectedPlayers[i].name + '</td>' +
                '<td><input type="text" name="g-' + selectedPlayers[i].playerId + '" class="stat_table_input" value="1"></td>' +
                '<td><input type="text" name="ab-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="r-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="h-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="b2-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="b3-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="hr-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="rbi-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="bb-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="so-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="sb-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="cs-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="ibb-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="hbp-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="sacb-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '<td><input type="text" name="sacf-' + selectedPlayers[i].playerId + '" class="stat_table_input"></td>' +
                '</tr>';
        }

        // need to add the form and the table
        var htmlContent = '<form method="post" id="addPitchersForm">' +
            '<table class="bcb_table">' +
            '<thead>' +
            '<tr class="firstRow">' +
            '<th class="firstCell">Name</th>' +
            '<th>G</th>' +
            '<th>AB</th>' +
            '<th>R</th>' +
            '<th>H</th>' +
            '<th>2B</th>' +
            '<th>3B</th>' +
            '<th>HR</th>' +
            '<th>RBI</th>' +
            '<th>BB</th>' +
            '<th>SO</th>' +
            '<th>SB</th>' +
            '<th>CS</th>' +
            '<th>IBB</th>' +
            '<th>HBP</th>' +
            '<th>SAC-B</th>' +
            '<th>SAC-F</th>' +
            '<th>PA</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody id="hittersTableBody">' +
            bodyContent +
            '</tbody>' +
            '</table>' +

            '<input type="hidden" name="addHittersSubmitForm">' +
            '<button type="submit"  id="submitAddHittersButton" class="fancyButton fancyButton-hvr">Save</button>';

        $('#hittersBoxElement').html(htmlContent);
    } else {
        // add to the body element

        // first need to get a list of all the elements even if not selected - if not selected and it's already in table we should remove it
        var allPlayers = [];
        $( ".player_pop_up_item" ).each(function() {
            var checkbox = $( this ).find('input');
            var player = {};
            player["playerId"] = checkbox.val();
            player["name"] = $( this ).find('span').html();
            if(checkbox.is(":checked")) {
                player['isSelected'] = true;
            } else {
                player['isSelected'] = false;
            }
            allPlayers.push(player);
        });

        var newRowsContent = "";
        for (var j = 0; j < allPlayers.length; ++j) {
            if($('#' + allPlayers[j].playerId + '-Stats').length > 0) {// row for this player already exists
                if(allPlayers[j].isSelected == false) {
                    $('#' + allPlayers[j].playerId + '-Stats').remove();
                }
            } else {
                if(allPlayers[j].isSelected == true) {
                    newRowsContent += '<tr id="' + allPlayers[j].playerId + '-Stats">' +
                        '<td class="firstCell"><input type="hidden" name="hiddenIdentifier[]" value="' + allPlayers[j].playerId + '">' + allPlayers[j].name + '</td>' +
                        '<td><input type="text" name="g-' + allPlayers[j].playerId + '" class="stat_table_input" value="1"></td>' +
                        '<td><input type="text" name="ab-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="r-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="h-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="b2-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="b3-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="hr-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="rbi-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="bb-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="so-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="sb-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="cs-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="ibb-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="hbp-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="sacb-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '<td><input type="text" name="sacf-' + allPlayers[j].playerId + '" class="stat_table_input"></td>' +
                        '</tr>';
                }
            }
        }

        $('#hittersTableBody').html($('#hittersTableBody').formhtml() + newRowsContent);
    }
    $('#lightbox').hide();
});


$( "#loggerElement.logged_out" ).click(function() {
    $('#lightbox').show();
});


$("#cancelLoginButton").click(function() {
    $('#name').val("");
    $('#password').val("");
    $('#loginErrorMessage').html("");
    $('#lightbox').hide();
});

$("##loggerElement.logged_in").click(function() {
    $.ajax({
        type : "POST",
        url : "/logger/logout",
        success : function(result) {
            window.location.reload();
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("ERROR");
        }
    });
});

$("#submitLoginButton").click(function() {
    $.ajax({
        type : "POST",
        url : "/logger/login",
        data : {
            "name" :  $('#name').val(),
            "password" :  $('#password').val()
        },
        success : function(result) {
            result = jQuery.parseJSON(result);
            if(result.error) {
                $('#loginErrorMessage').html("Invalid Name/Password");
            } else {
                window.location.reload();
            }
        },
        error : function(XMLHttpRequest, textStatus, errorThrown) {
            alert("ERROR");
        }
    });
});


$( document ).ready(function() {
    function pitchingTableRefresh() {
        $.ajax({
            url: 'http://brockportclubbaseballstats.appspot.com/game/siteTransfer?type=pitching&season='+$('#season').val(),
            jsonpCallback: 'callback',
            dataType: 'jsonp',
            success: function(data){
                var content = "";
                if(data.stats.length == 1) {
                    content = "";
                } else {
                    for(var i = 0; i < data.stats.length; ++i) {
                        var item = data.stats[i];
                        var name = item.name;

                        var firstLine = "";
                        if(item.displayName == "Total") {
                            firstLine = '<td class="total" style="color: #F1F0EB; font-weight: bold; text-align: center">' + item.displayName + '</td>';
                        } else {
                            firstLine = '<td class="player" style="color: #F1F0EB; text-align: center">' + item.displayName + '</td>';
                        }
                        content += '<tr>' +
                            firstLine +
                            '<td class="w" style="text-align: center;" id="'+name+'-w">' + item.w + '</td>' +
                            '<td class="l" style="text-align: center;" id="'+name+'-l">' + item.l + '</td>' +
                            '<td class="era" style="text-align: center;" id="'+name+'-era">' + item.era + '</td>' +
                            '<td class="g" style="text-align: center;" id="'+name+'-g">' + item.g + '</td>' +
                            '<td class="gs" style="text-align: center;" id="'+name+'-gs">' + item.gs + '</td>' +
                            '<td class="cg" style="text-align: center;" id="'+name+'-cg">' + item.cg + '</td>' +
                            '<td class="sv" style="text-align: center;" id="'+name+'-sv">' + item.sv + '</td>' +
                            '<td class="svo" style="text-align: center;" id="'+name+'-svo">' + item.svo + '</td>' +
                            '<td class="ip" style="text-align: center;" id="'+name+'-ip">' + item.ip + '</td>' +
                            '<td class="h" style="text-align: center;" id="'+name+'-h">' + item.h + '</td>' +
                            '<td class="r" style="text-align: center;" id="'+name+'-r">' + item.r + '</td>' +
                            '<td class="er" style="text-align: center;" id="'+name+'-er">' + item.er + '</td>' +
                            '<td class="hr" style="text-align: center;" id="'+name+'-hr">' + item.hr + '</td>' +
                            '<td class="bb" style="text-align: center;" id="'+name+'-bb">' + item.bb + '</td>' +
                            '<td class="so" style="text-align: center;" id="'+name+'-so">' + item.so + '</td>' +
                            '<td class="whip" style="text-align: center;" id="'+name+'-whip">' + item.whip + '</td>' +
                            '<td class="sho" style="text-align: center;" id="'+name+'-sho">' + item.sho + '</td>' +
                            '<td class="hbp" style="text-align: center;" id="'+name+'-hbp">' + item.hbp + '</td>' +
                            '<td class="wper" style="text-align: center;" id="'+name+'-wper">' + item.wper + '</td>' +
                            '<td class="kper9" style="text-align: center;" id="'+name+'-kper9">' + item.kper9 + '</td>' +
                            '<td class="wper9" style="text-align: center;" id="'+name+'-wper9">' + item.wper9 + '</td>' +
                            '<td class="hper9" style="text-align: center;" id="'+name+'-hper9">' + item.hper9 + '</td>' +
                            '<td class="kbb" style="text-align: center;" id="'+name+'-kbb">' + item.kbb + '</td>' +
                            '</tr>';
                    }
                }

                $('#pitchingTableBody').html(content);
            }
        });
    }

    function hittingTableRefresh() {
        $.ajax({
            url: 'http://brockportclubbaseballstats.appspot.com/game/siteTransfer?type=hitting&season='+$('#season').val(),
            jsonpCallback: 'callback',
            dataType: 'jsonp',
            success: function(data){
                var content = "";
                if(data.stats.length == 1) {
                    content = "";
                } else {
                    for(var i = 0; i < data.stats.length; ++i) {
                        var item = data.stats[i];
                        var name = item.name;

                        var firstLine = "";
                        if(item.displayName == "Total") {
                            firstLine = '<td class="total" style="color: #F1F0EB; font-weight: bold; text-align: center">' + item.displayName + '</td>';
                        } else {
                            firstLine = '<td class="player" style="color: #F1F0EB; text-align: center">' + item.displayName + '</td>';
                        }
                        content += '<tr>' +
                            firstLine +
                            '<td class="g" style="text-align: center;" id="'+name+'-g">' + item.g + '</td>' +
                            '<td class="ab" style="text-align: center;" id="'+name+'-ab">' + item.ab + '</td>' +
                            '<td class="r" style="text-align: center;" id="'+name+'-r">' + item.r + '</td>' +
                            '<td class="h" style="text-align: center;" id="'+name+'-h">' + item.h + '</td>' +
                            '<td class="2b" style="text-align: center;" id="'+name+'-2b">' + item["2b"] + '</td>' +
                            '<td class="3b" style="text-align: center;" id="'+name+'-3b">' + item["3b"] + '</td>' +
                            '<td class="hr" style="text-align: center;" id="'+name+'-hr">' + item.hr + '</td>' +
                            '<td class="rbi" style="text-align: center;" id="'+name+'-rbi">' + item.rbi + '</td>' +
                            '<td class="bb" style="text-align: center;" id="'+name+'-bb">' + item.bb + '</td>' +
                            '<td class="so" style="text-align: center;" id="'+name+'-so">' + item.so + '</td>' +
                            '<td class="sb" style="text-align: center;" id="'+name+'-sb">' + item.sb + '</td>' +
                            '<td class="cs" style="text-align: center;" id="'+name+'-cs">' + item.cs + '</td>' +
                            '<td class="avg" style="text-align: center;" id="'+name+'-avg">' + item.avg + '</td>' +
                            '<td class="obp" style="text-align: center;" id="'+name+'-obp">' + item.obp + '</td>' +
                            '<td class="slg" style="text-align: center;" id="'+name+'-slg">' + item.slg + '</td>' +
                            '<td class="ops" style="text-align: center;" id="'+name+'-ops">' + item.ops + '</td>' +
                            '<td class="ibb" style="text-align: center;" id="'+name+'-ibb">' + item.ibb + '</td>' +
                            '<td class="hbp" style="text-align: center;" id="'+name+'-hbp">' + item.hbp + '</td>' +
                            '<td class="sacb" style="text-align: center;" id="'+name+'-sacb">' + item.sacb + '</td>' +
                            '<td class="sacf" style="text-align: center;" id="'+name+'-sacf">' + item.sacf + '</td>' +
                            '<td class="tb" style="text-align: center;" id="'+name+'-tb">' + item.tb + '</td>' +
                            '<td class="xbh" style="text-align: center;" id="'+name+'-xbh">' + item.xbh + '</td>' +
                            '<td class="pa" style="text-align: center;" id="'+name+'-pa">' + item.pa + '</td>' +
                            '</tr>';
                    }
                }

                $('#hittingTableBody').html(content);
            }
        });
    }

    if($('#pitchingTable').length > 0 || $('#hittingTable').length > 0) {
        $('.stats_table th').click(function() {
            var tableId = $(this).parents("table").attr("id");
            console.log(tableId);
            var sortType = $(this).attr("data-sortType");
            if(!sortType) {
                return;
            }

            if($(this).attr("data-sorted") == true) {
                if(tableId == "pitchingTable") {
                    pitchingTableRefresh();
                } else {
                    hittingTableRefresh();
                }
                return;
            } else {
                $('.stats_table th').removeAttribute("data-sorted");
                $(this).attr("data-sorted", true);
            }

            var sortColumn = function(a, b) {
                console.log(sortType);
                var aTd = $(a).find("."+sortType);
                var bTd = $(b).find("."+sortType);

                console.log("aTd val: " + $(aTd).html() + "bTd val: " + $(bTd).html());

                var aItemId = $(aTd).attr("id");
                var bItemId = $(bTd).attr("id");

                if (aItemId.indexOf("total") == 0) {
                    console.log("Total -> always greater");
                    return 1;
                } else if(bItemId.indexOf("total") == 0) {
                    return -1;
                } else if(isNaN($(aTd).html())) {
                    console.log("Not a number -> greater: " + $(aTd).html());
                    return 1;
                } else if(isNaN($(bTd).html())) {
                    return -1;
                }


                if(parseFloat($(aTd).html(),10) == parseFloat($(bTd).html(),10)) {
                    console.log("equal");
                    return 0;
                } else if(parseFloat($(aTd).html(),10) > parseFloat($(bTd).html(), 10)) {
                    console.log("greater");
                    if(tableId == "pitchingTable") {
                        if(sortType == "era" || sortType == "whip") {
                            return 1;
                        } else {
                            return -1;
                        }
                    }
                    return -1;
                } else {
                    console.log("smaller");
                    if(tableId == "pitchingTable") {
                        if(sortType == "era" || sortType == "whip") {
                            return -1;
                        } else {
                            return 1;
                        }
                    }
                    return 1;
                }
            };


            var tableRowsList = $('.stats_table tbody').find("tr");
            tableRowsList.sort(sortColumn);
            var sortedRows = "";
            for (var i = 0; i < tableRowsList.length; i++) {
                sortedRows += tableRowsList[i].outerHTML;
            }
            $('.stats_table tbody').html(sortedRows);
        });
    }

    if($('#pitchingTable').length > 0) {
        pitchingTableRefresh();

        $('#season').on('change', function() {
            pitchingTableRefresh();
        });
    }

    if($('#hittingTable').length > 0) {
        hittingTableRefresh();

        $('#season').on('change', function() {
            hittingTableRefresh();
        });
    }
});


