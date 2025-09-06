import { config } from "../../config";

export async function createContact(newContact) {
  try {
    const response = await fetch(`${config.apiBaseUrl}/contact-me`, {
      method: "POST",
      body: JSON.stringify(newContact),
      headers: {
        'Content-Type': 'application/json',
        'accept': 'application/json',
      },
    });
    const { data } = await response.json();
    return data;
  } catch (error) {
    console.error(error.message);
    throw Error(error.message);
  }
}