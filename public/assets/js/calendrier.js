window.onload = () => {
    let calendarEl = document.getElementById("calendar");

    let xmlhttp = new XMLHttpRequest()

    xmlhttp.onreadystatechange = () => {
        if (xmlhttp.readyState == 4) {
            if (xmlhttp.status == 200) {
                let evenements = JSON.parse(xmlhttp.responseText);

                // on instancie le calendrier
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    // on appelle les composants
                    slotMinTime: '08:00',
                    slotMaxTime: '22:00',
                    initialView: "dayGridMonth",
                    locale: "fr",
                    timeZone: 'Europe/Paris',
                    headerToolbar: {
                        left: "prev,next today",
                        center: "title",
                        right: "timeGridDay,timeGridWeek,dayGridMonth,list",
                    },
                    buttonText: {
                        today: "Aujourd'hui",
                        month: "Mois",
                        week: "Semaine",
                        day: "Jour",
                        list: "Liste",
                    },
                    events: evenements.map(evenement => {
                        // convertir les propriétés de date en objets Date
                        let start = new Date(evenement.start);
                        let end = new Date(evenement.end);

                        // ajouter les horaires de début et de fin à la date de début et de fin
                        let startHoursMinutes = evenement.startTime.split(":");
                        start.setHours(startHoursMinutes[0]);
                        start.setMinutes(startHoursMinutes[1]);

                        let endHoursMinutes = evenement.endTime.split(":");
                        end.setHours(endHoursMinutes[0]);
                        end.setMinutes(endHoursMinutes[1]);

                        // créer un nouvel objet événement avec les propriétés mises à jour
                        return {
                            id: evenement.id,
                            title: evenement.title,
                            start: start,
                            end: end
                        };
                    }),
                    allDaySlot: false,
                    // nowIndicator: true,
                    editable: true, // autoriser le glisser-déposer
                    eventResizableFromStart: true,
                    slotDuration: '00:15',
                      
                    eventResize: function(info) {
                        // déclenché lorsque l'utilisateur change la durée d'un événement
                        // mettre à jour les dates de début et de fin de l'événement dans la base de données
                        // en utilisant les propriétés info.event.start et info.event.end
                        let xhr = new XMLHttpRequest();
                        xhr.open('PATCH', 'http://127.0.0.1:8000/calendar/events/' + info.event.id, true);
                        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                        xhr.send(JSON.stringify({
                            start: info.event.start,
                            end: info.event.end
                        }));
                    },
                    eventDrop: function(info) {
                        
                        // déclenché lorsque l'utilisateur déplace un événement vers un autre emplacement
                        // mettre à jour les dates de début et de fin de l'événement dans la base de données
                        // en utilisant les propriétés info.event.start et info.event.end
                        let xhr = new XMLHttpRequest();
                        xhr.open('PATCH', 'http://127.0.0.1:8000/calendar/events/' + info.event.id, true);
                        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
                        xhr.send(JSON.stringify({
                            start: info.event.start,
                            end: info.event.end
                        }));
                    }
                
                })

                calendar.render()
            }
        }
    };

    xmlhttp.open("get", "http://127.0.0.1:8000/calendar/events", true)
    xmlhttp.send(null)
};