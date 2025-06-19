// backend.js (Node.js)
const { google } = require('googleapis');
const express = require('express');
const cors = require('cors');

const app = express();
app.use(cors());

const auth = new google.auth.GoogleAuth({
    keyFile: 'sistemadefidelizacion-0d4bd6126167.json',
    scopes: ['https://www.googleapis.com/auth/calendar.readonly'],
});

app.get('/api/busy-hours', async (req, res) => {
    try {
        const calendar = google.calendar({ version: 'v3', auth });
        const calendarId = 'barberhomeluisvilladiego20@gmail.com';
        
        const response = await calendar.events.list({
            calendarId,
            timeMin: new Date().toISOString(),
            maxResults: 2500,
            singleEvents: true,
            orderBy: 'startTime',
        });

        const hourCounts = response.data.items.reduce((acc, event) => {
            if (event.start.dateTime) {
                const hour = new Date(event.start.dateTime).getHours();
                acc[hour] = (acc[hour] || 0) + 1;
            }
            return acc;
        }, {});

        // Ordenar por hora
        const sortedHours = Object.keys(hourCounts)
            .sort((a, b) => a - b)
            .reduce((obj, key) => {
                obj[key] = hourCounts[key];
                return obj;
            }, {});

        res.json(sortedHours);

    } catch (error) {
        console.error('Error:', error);
        res.status(500).json({ error: 'Error al obtener datos' });
    }
});

app.listen(3000, () => {
    console.log('Servidor API corriendo en http://localhost:3000');
});