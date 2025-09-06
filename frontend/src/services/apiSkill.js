import { config } from "../../config";


export async function getSkills({ signal } = {}) {

  const response = await fetch(`${config.apiBaseUrl}/skills`, { signal });
  if (!response.ok) {
    let message = `Failed to fetch Skills (status ${response.status})`;
    try {
      const result = await response.json();
      if (result?.message) message = result.message;
    } catch {
      console.error("Error parsing error response");
    }
    throw new Response(message, { status: response.status });
  }
  const { data } = await response.json();
  return data;
}