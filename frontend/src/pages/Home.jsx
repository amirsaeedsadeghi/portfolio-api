import { useLoaderData, useOutletContext } from "react-router-dom";
import AboutMe from "../features/home/AboutMe";
import ContactMe from "../features/home/ContactMe";
import Hero from "../features/home/Hero";
import Projects from "../features/home/projects/Projects";
import { getSkills } from "../services/apiSkill";
import { getProjects } from "../services/apiProject";
import { getToken } from "../services/apiHoneypot";

function Home() {
    const { aboutMe } = useOutletContext();
    const { skills, projects, honeypot } = useLoaderData();

    return (
        <>
            <Hero aboutMe={aboutMe} />
            <AboutMe aboutMe={aboutMe} skills={skills} />
            <Projects projects={projects} />
            <ContactMe honeypot={honeypot} />
        </>
    );
}

export default Home;

// eslint-disable-next-line
export async function loader({ request }) {
    const { signal } = request;
    const [skills, projects, honeypot] = await Promise.all([
        getSkills({ signal }),
        getProjects({ signal }),
        getToken({ signal }),
    ]);
    return { skills, projects, honeypot };
}
