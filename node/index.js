
// Node.js example

import express from 'express';
import dotenv from 'dotenv';
 import bodyParser from 'body-parser';
 import { GoogleGenerativeAI } from '@google/generative-ai';

 dotenv.config();
 const apiKey = process.env.API_KEY;

const app = express();
const PORT = 3000;

app.use(bodyParser.json());

const genAI = new GoogleGenerativeAI(apiKey);
const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

app.post('/summarize', async (req, res) => {
  const { prompt } = req.body;

  try {
      const result = await model.generateContent(prompt);
      res.json({ summary: result.response.text() });
  } catch (error) {
      console.error("Error generating summary:", error);
      res.status(500).send("Error generating summary");
  }
});




app.listen(PORT, () => {
  console.log(`Node.js server running on http://localhost:${PORT}`);
});

//------------------------------------------------------------------------------

