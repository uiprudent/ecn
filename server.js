const express = require('express');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

// Serve static files from the current directory
app.use(express.static('.'));

// Handle routes for HTML files
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.get('/contact', (req, res) => {
  res.sendFile(path.join(__dirname, 'contact.html'));
});

app.get('/team', (req, res) => {
  res.sendFile(path.join(__dirname, 'team.html'));
});

app.get('/research-centers', (req, res) => {
  res.sendFile(path.join(__dirname, 'research-centers.html'));
});

app.get('/reports', (req, res) => {
  res.sendFile(path.join(__dirname, 'reports.html'));
});

app.get('/papers', (req, res) => {
  res.sendFile(path.join(__dirname, 'papers.html'));
});

app.get('/journals', (req, res) => {
  res.sendFile(path.join(__dirname, 'journals.html'));
});

app.get('/releases', (req, res) => {
  res.sendFile(path.join(__dirname, 'releases.html'));
});

app.get('/bulletins', (req, res) => {
  res.sendFile(path.join(__dirname, 'bulletins.html'));
});

app.get('/news', (req, res) => {
  res.sendFile(path.join(__dirname, 'news.html'));
});

// Catch all handler for other routes
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});