// import express from 'express';
// import bodyParser from 'body-parser';
// import fetch from 'node-fetch';

// const app = express();
// const PORT = 3000;

// app.use(bodyParser.json());

// // Route to handle text summarization
// app.post('/summarize', async (req, res) => {
//   try {
//     // Fetch the static text from the PHP file
//     const phpResponse = await fetch('http://localhost/AI-Powered-Note-Taking-Website/habiba.php');
//     const phpData = await phpResponse.json();

//     if (!phpData.text) {
//       throw new Error('Static text not found in PHP response');
//     }

//     const staticText = phpData.text;

//     // Simulate summarization logic
//     const summary = simulateSummarization(staticText);

//     console.log('Summary generated successfully:', summary);
//     res.json({ summary });
//   } catch (error) {
//     console.error('Error generating summary:', error.message);
//     res.status(500).send('Error generating summary');
//   }
// });

// // Simulated summarization function (replace with actual summarization API logic)
// function simulateSummarization(text) {
//   const words = text.split(' ');
//   return words.length > 10
//     ? words.slice(0, 10).join(' ') + '...'
//     : text; // Truncate text to first 10 words as a simulation
// }

// app.listen(PORT, () => {
//   console.log(`Server running at http://localhost:${PORT}/summarize`);
// });

import express from 'express';
 import bodyParser from 'body-parser';
 import { GoogleGenerativeAI } from '@google/generative-ai';
const app = express();
const PORT = 3000;

app.use(bodyParser.json());

const genAI = new GoogleGenerativeAI("AIzaSyC2RYQH8j-dD2U02CUitJIUS1Fd8DyJI5I");
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


// app.post('/generate-mcq', async (req, res) => {
//   const { prompt } = req.body;

//   if (!prompt) {
//     return res.status(400).send("Error: Prompt is required.");
//   }

//   try {
//     const mcqPrompt = `Generate a multiple-choice question based on the following text: ${prompt}`;
//     const result = await model.generateContent(mcqPrompt);

//     const mcqText = result.response.text();
//     // Basic parsing to extract the question and choices
//     const [question, ...choices] = mcqText.split('\n').filter(Boolean);

//     res.json({
//       question,
//       choices: choices.map(choice => choice.trim())
//     });
//   } catch (error) {
//     console.error("Error generating MCQ:", error.message);
//     res.status(500).send("Error generating MCQ.");
//   }
// });

app.listen(PORT, () => {
  console.log(`Node.js server running on http://localhost:${PORT}`);
});

//------------------------------------------------------------------------------

// import express from 'express';
// import bodyParser from 'body-parser';
// import { GoogleGenerativeAI } from '@google/generative-ai';

// const app = express();
// const PORT = 3000;

// app.use(bodyParser.json());

// const genAI = new GoogleGenerativeAI("AIzaSyC2RYQH8j-dD2U02CUitJIUS1Fd8DyJI5I");
// const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

// const generateContent = async (prompt) => {
//     const result = await model.generateContent({ input: { text: prompt } });
//     if (result && result.generations && result.generations.length > 0) {
//         return result.generations[0].text.trim();
//     } else {
//         throw new Error("Invalid response format from API");
//     }
// };

// app.post('/summarize', async (req, res) => {
//     const { text } = req.body;
//     const prompt = `Summarize the following text: ${text}`;

//     try {
//         const summary = await generateContent(prompt);
//         res.json({ summary });
//     } catch (error) {
//         console.error("Error generating summary:", error.message);
//         res.status(500).send("Error generating summary");
//     }
// });

// app.post('/generate-qa', async (req, res) => {
//     const { text } = req.body;
//     const prompt = `Generate a Q&A based on the following text: ${text}`;

//     try {
//         const qa = await generateContent(prompt);
//         res.json({ qa });
//     } catch (error) {
//         console.error("Error generating Q&A:", error.message);
//         res.status(500).send("Error generating Q&A");
//     }
// });

// app.post('/generate-mc', async (req, res) => {
//     const { text } = req.body;
//     const prompt = `Generate multiple choice questions based on the following text: ${text}`;

//     try {
//         const mc = await generateContent(prompt);
//         res.json({ mc });
//     } catch (error) {
//         console.error("Error generating multiple choice questions:", error.message);
//         res.status(500).send("Error generating multiple choice questions");
//     }
// });

// app.listen(PORT, () => {
//     console.log(`Node.js server running on http://localhost:${PORT}`);
// });
