<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\NavbarItem;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use App\Models\Stack;
use App\Models\User;
use App\Models\AboutMe;
use Illuminate\Database\Eloquent\Model;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Amirsaeed Sadeghi Komjani',
            'email' => 'amirsaeed.sadeghi@gmail.com',
            'image' => 'storage/images/users/profile.webp',
            'role' => 'admin',
        ]);
        User::factory(5)->create();


        // Fill navbar_items 
        $navbarItems = [
            ['label' => 'Home', 'link' => '/', 'order' => 1],
            ['label' => 'About', 'link' => '/#about', 'order' => 2],
            ['label' => 'Projects', 'link' => '/#projects', 'order' => 3],
            ['label' => 'Contact', 'link' => '/#contact', 'order' => 4],
        ];
        $this->fillDB($navbarItems, NavbarItem::class);

        // Fill stacks
        $stacks = [
            ['name' => 'php', 'image' => 'storage/images/stacks/php.png'],
            ['name' => 'mysql', 'image' => 'storage/images/stacks/mysql.png'],
            ['name' => 'react', 'image' => 'storage/images/stacks/react.png'],
            ['name' => 'vite', 'image' => 'storage/images/stacks/vite.png'],
            ['name' => 'tailwind', 'image' => 'storage/images/stacks/tailwind.png'],
            ['name' => 'bootstrap', 'image' => 'storage/images/stacks/bootstrap.png'],
            ['name' => 'pug', 'image' => 'storage/images/stacks/pug.png'],
            ['name' => 'laravel', 'image' => 'storage/images/stacks/laravel.png'],
            ['name' => 'blade', 'image' => 'storage/images/stacks/blade.png'],
            ['name' => 'webpack', 'image' => 'storage/images/stacks/webpack.png'],
            ['name' => 'mongodb', 'image' => 'storage/images/stacks/mongodb.png'],
            ['name' => 'nodejs', 'image' => 'storage/images/stacks/nodejs.png'],
            ['name' => 'express', 'image' => 'storage/images/stacks/express.png'],
            ['name' => 'nextjs', 'image' => 'storage/images/stacks/nextjs.png'],
            ['name' => 'styled-components', 'image' => 'storage/images/stacks/styled-components.png'],
            ['name' => 'redux', 'image' => 'storage/images/stacks/redux.png'],
            ['name' => 'react-query', 'image' => 'storage/images/stacks/react-query.png'],
            ['name' => 'postgresql', 'image' => 'storage/images/stacks/postgresql.png'],

        ];
        $this->fillDB($stacks, Stack::class);

        // Fill Project
        $projects = [
            [
                'title' => 'Personal Portfolio Backend',
                'summary' => 'Full-stack app (Laravel + React) showcasing JWT auth, Clean Architecture, and custom UI.',
                'description' => '<p>This project is both a personal portfolio and a technical showcase of modern backend and frontend engineering.</p>
                    <p><strong>Backend:</strong> Built with Laravel 10 and PHP 8.2, following Clean Architecture principles. It features a clear MVC + layered design, Repository pattern with Interfaces and Factories (DIP), JWT authentication, FormRequests for validation and authorization, Policies enforced in the Service layer, dynamic Query Filters, Traits for reusable behaviors, and centralized Exception Handling with a uniform error schema.</p>
                    <p>Rich API documentation is auto-generated with <strong>Scribe 5.x</strong> (HTML, Postman, OpenAPI). Extensive OOP, Design Patterns, and PHP 8.1 features (Enums, typed properties, etc.) are used throughout.</p>
                    <p><strong>Frontend:</strong> Developed entirely from scratch with React 19, React Router v6.30, and Tailwind CSS v4. It follows modular design with reusable building blocks, applying the Compound Component Pattern and utility-first styling for a clean, scalable, and production-ready UI.</p>
                    <p><strong>Architecture:</strong> Controllers remain thin, delegating to Services. Services encapsulate business logic and apply Policies. Repositories abstract data access and are decoupled from infrastructure using Interfaces and Factories. Filters provide safe, composable query logic (LIKE, IN, ranges, sorting, pagination). FormRequests unify validation, authorization, and documentation. Resources transform outputs into consistent JSON responses.</p>
                    <p><strong>Testing:</strong> PHPUnit is used for unit and feature tests. The project includes tests for repositories, services, validation, and API endpoints.</p>
                    <p><strong>Roadmap:</strong> The project is evolving towards a custom in-house mini-framework with its own ORM, advanced OOP design, and disciplined Git flow. Planned enhancements include route-level middleware, JWT-based policies, CI/CD pipelines with GitHub Actions, and performance benchmarks.</p>
                    <p>This codebase demonstrates strong attention to <em>structure, clarity, long-term maintainability, and developer experience</em>. It reflects an engineering mindset focused on deep understanding, architectural discipline, and writing code that makes sense for the next developer.</p>',
                'client' => 'Personal Project',
                'demo_link' => 'https://portfolio-demo-link.com',
                'category' => 'Framework',
                'primary_image' => 'storage/images/projects/portfolio/portfolio-1.webp',
                'order' => 1,
                'role' => 'Full-Stack Web Developer',
                'start_date' => '2025-07-02'
            ],
            [
                'title' => 'Atryad Company Website',
                'summary' => 'Corporate website with a workflow engine and internal CRM using pure PHP.',
                'description' => '<p>The Atryad company website may appear, at first glance, to be a standard corporate website—but beneath the surface, it includes a powerful, fully custom-built admin panel that supports key business processes such as project workflows, order tracking, customer notifications, and a lightweight internal CRM system for handling consultation feedback and execution monitoring.</p>
                    <p>The application was developed in pure PHP without any frameworks, following the MVC architecture.</p>
                    <h3>Core Functionalities and Architecture</h3>
                    <ul>
                    <li>The internal workflow engine manages the lifecycle of every project step-by-step using a combination of architectural design patterns:</li>
                    <li>Chain of Responsibility – to route each step of a workflow to the appropriate team member.</li>
                    <li>State Pattern – to track and manage the status of each project dynamically.</li>
                    <li>Policy (RBAC) – to enforce role-based access control and ensure that each user can only act within their defined scope.</li>
                    <li>A custom Observer Pattern was used to notify both clients and internal staff at key stages defined by the company. Notifications are sent via SMS and Email, based on user roles and the status of the workflow.</li>
                    <li>The mini-CRM system includes:</li>
                    <ul>
                        <li>Administrative correspondence logging.</li>
                        <li>Status-based reminders.</li>
                        <li>Feedback capture.</li>
                        <li>Internal tracking of client interaction history.</li>
                    </ul>
                    </ul>
                    <p>Designing and implementing even a lightweight CRM brings its own complexities, especially when all internal communications and operations need to be centralized and auditable.</p>
                    <h3>Background and Evolution</h3>
                    <p>Drawing on my experience in desktop software development from a previous role at The One Rak—where I worked on large-scale internal systems compliant with Iranian governmental bidding and tax regulations—this web-based implementation was more streamlined and efficient. However, this also raised architectural questions: after early internal deployment, Avamafarin decided to make the workflow engine dynamic and reusable, so it could be adapted for other clients with minimal changes. This required reconsidering earlier design choices and selecting flexible design patterns capable of supporting evolving business logic.</p>
                    <h3>UI and Styling</h3>
                    <ul>
                    <li>The public-facing website uses Bootstrap for layout and styling.</li>
                    <li>The admin panel UI was designed and implemented entirely from scratch, tailored exclusively for Avamafarin’s needs—without any pre-built templates.</li>
                    </ul>
                    <p>A live demo is available via the demo link for further evaluation.</p>',
                'client' => 'Avamafarin',
                'demo_link' => 'https://atryadmodiran.ir',
                'category' => 'Mini-CRM, Company Website',
                'primary_image' => 'storage/images/projects/atryad/atryad-1.webp',
                'order' => 7,
                'role' => 'Full-Stack Web Developer',
                'start_date' => '2021-10-30',
            ],
            [
                'title' => 'Outworks Marketplace',
                'summary' => 'Service platform with bidding, tax engine, and milestone payments for Canada.',
                'description' => '<p>Outworks is a custom-built web application for a Canadian startup, originally intended to be developed using the MERN stack. However, due to alignment issues with an outsourced frontend team (from Avamafarin), the frontend was eventually rebuilt using Pug template engine for the MVP version. As the technical lead and project manager responsible for architecture and delivery in Iran, I had to complete and deploy the MVP under tight deadlines using this adjusted tech stack.</p>
                    <p>Building a web application of this scale remotely—especially for an international startup—presented significant challenges and valuable lessons. One such challenge was adapting to the cultural differences in service payments: unlike in Iran, where service providers are typically paid after job completion, Canadian clients required payment either upfront or in defined milestones. This led to the design and implementation of a milestone-based payment system, fully integrated into the platform.</p>
                    <p>Another challenge involved implementing a province-specific taxation model. Unlike Iran’s uniform tax system, Canada applies different VAT/TAX rates across its provinces. As a result, we built a configurable tax engine with province-level granularity and future-editing capabilities to ensure long-term maintainability.</p>
                    <p>The platform follows a bidding-style service model, where customers receive offers from service providers based on ratings, satisfaction history, and pricing, enabling them to choose the best fit for their task.</p>
                    <p>This project took nearly one full year to build and stabilize. Due to a Non-Disclosure Agreement (NDA), many of the platform’s advanced and creative features are confidential. However, the demo version, accessible via the demo button, provides a clear demonstration of the core structure and functionality.</p>
                    <h3>User Guide – How to Test the Application</h3>
                    <ol>
                    <li>Use two different browsers to log in with the following accounts.<br>
                    (Authentication is handled via JWT tokens)<br>
                    • Customer (Service Requestor)<br>
                    Email: usertester@test.com<br>
                    Password: password123<br>
                    • Service Provider<br>
                    Email: operatortester@test.com<br>
                    Password: password123</li>
                    <li>Service providers can only view service requests that match their registered expertise.<br>
                    To ensure the test works properly, the customer should create a request in one of the following categories:<br>
                    Office, Home, or HVAC</li>
                    <li>As the customer, after logging in, go to:<br>
                    Requests → Request a Service<br>
                    Fill in the form to submit a new service request, and wait for offers.</li>
                    <li>In another browser, log in as the service provider. Then navigate to:<br>
                    Requests → Show Requests<br>
                    Here, providers can view incoming service requests and submit pricing proposals.<br>
                    (The system supports multi-stage milestone-based payments.)</li>
                    <li>The customer now goes to:<br>
                    Requests → Suggestions<br>
                    to view and compare proposals. Once a proposal is accepted, the process moves to:<br>
                    Requests → In Process<br>
                    where the full workflow—including final invoice and payment—is tracked until completion.<br>
                    (Email notifications are triggered if valid email addresses are provided.)</li>
                    <li>To test the Stripe integration, use the test card below for a successful dummy transaction:<br>
                    • Card Number: 4242 4242 4242 4242<br>
                    • Expiration Date: Any valid future date</li>
                    <li>The platform allows full or partial invoice generation with itemized details.<br>
                    Tax is automatically calculated based on the customer’s selected province-specific VAT/TAX rules.</li>
                    <li>Rating the service provider is mandatory. After completion, the customer can submit feedback via:<br>
                    Requests → In Process</li>
                    </ol>
                    <p>As mentioned earlier, to preserve client confidentiality, the UI, business name, branding, and slogans have all been replaced for demo purposes. Many innovative and proprietary features are not included in the public version due to the NDA.</p>',
                'client' => 'Canadian Startup',
                'demo_link' => 'https://outworks.ir',
                'category' => 'Web App',
                'primary_image' => 'storage/images/projects/outworks/outworks-1.webp',
                'order' => 2,
                'role' => 'CTO and Full-Stack Web Developer',
                'start_date' => '2020-03-21',
            ],
            [
                'title' => 'Oud Wood E-commerce',
                'summary' => 'Laravel-based e-commerce with dynamic pricing and custom admin features.',
                'description' => '<p>Oud Wood is a responsive, fully-featured e-commerce web application built with Laravel on the backend and Blade + Bootstrap on the frontend. All styles are modularized using Sass, compiled and bundled via Laravel Mix and Webpack, ensuring maintainable and scalable UI development.</p>
                    <p>One of the standout features of this platform is its dynamic product pricing system. Products can have multiple variable attributes—such as size, scent, or packaging—and the final price is dynamically calculated based on selected combinations. This is achieved through a clean relational MySQL database design combined with smart use of Composite Pattern, Decorator Pattern, and Factory Pattern to handle complex pricing logic in a modular and extensible way.</p>
                    <h3>Key Features</h3>
                    <ul>
                    <li>Admin Panel with Role-Based Access Control (RBAC): Fine-grained CRUD permissions for both users and user groups.</li>
                    <li>User Registration with OTP: Reduces SMS costs by enabling login via password after OTP-based registration.</li>
                    <li>Discount & Promotion System: Admins can configure percentage- or amount-based discounts, apply time limits, and define usage caps. Promo codes are also supported.</li>
                    <li>Advanced Shipping Cost Management: Admins can configure tiered shipping fees for bulk orders that are automatically reflected in the user’s cart and invoice.</li>
                    <li>Maintenance Mode with Developer Backdoor: A unique capability where the system can be put into maintenance mode, displaying a custom notification page to all users. Developers, however, can access the full system using a secret URL parameter—an especially useful feature for businesses in volatile markets like Iran, where sudden price updates may not be legally permitted or operationally feasible.</li>
                    </ul>
                    <h3>Technical Implementation</h3>
                    <ul>
                    <li>Built with MVC + Clean Architecture principles to ensure separation of concerns and high code maintainability.</li>
                    <li>Many parts of the UI—especially in the admin panel—are enhanced using AJAX for a smoother user experience.</li>
                    <li>Both the storefront and admin dashboard are fully responsive, providing optimal functionality across devices.</li>
                    </ul>
                    <p>Due to an NDA (Non-Disclosure Agreement) signed with the company, further technical details cannot be publicly disclosed. However, a demo version preserving confidentiality is available via the demo button on the sidebar.</p>
                    <p>You can log in to the demo admin panel using the following credentials:<br>
                    • Username: 09876543210<br>
                    • Password: Password123@456</p>',
                'client' => 'Avamafarin',
                'demo_link' => 'https://demo.avamafarin.ir',
                'category' => 'E-Commerce',
                'primary_image' => 'storage/images/projects/oudwood/oudwood-1.webp',
                'order' => 3,
                'role' => 'CTO and Full-Stack Web Developer',
                'start_date' => '2022-06-29',
            ],
            [
                'title' => 'Pizza Bottura Ordering',
                'summary' => 'MERN stack SPA with multilingual and multi-currency pizza ordering system.',
                'description' => '<p>Pizza Bottura is a fully responsive Single Page Application (SPA) built with the MERN stack (MongoDB, Express, React, Node.js), designed for online pizza ordering across multiple languages: English, Persian, and Arabic.</p>
                    <p>Unlike standard e-commerce apps, this platform accounts for real-world pricing complexity in regions like Iran. Each pizza item has three distinct prices in Dirham (AED), Toman (IRR), and USD, allowing the restaurant owner to manage currency-based fluctuations and regulatory requirements.</p>
                    <h3>Key features include:</h3>
                    <ul>
                    <li>Cash-on-delivery (COD) system – customers pay upon receiving their order.</li>
                    <li>A dynamic form during checkout that collects user info, phone number, and live geolocation.</li>
                    <li>Priority preparation option – customers can pay an extra fee to expedite their order, either during checkout or anytime afterward.</li>
                    <li>A real-time order tracking feature allows customers to check the preparation status using their unique order ID.</li>
                    </ul>
                    <h3>Backend & Database</h3>
                    <ul>
                    <li>The backend is built with Express.js and follows strict RESTful API conventions.</li>
                    <li>The MongoDB schema is structured to support unlimited languages, making the platform easily extendable for future markets.</li>
                    <li>The internationalization (i18n) system is custom-built, inspired by Laravel’s trans() method, using separate key-value translation files loaded through a custom async Translation class. No third-party i18n libraries were used, ensuring full control and minimal dependencies.</li>
                    </ul>
                    <h3>Frontend Architecture</h3>
                    <ul>
                    <li>Built with React 18, styled entirely using Tailwind CSS with full mobile responsiveness.</li>
                    <li>Uses React Router v6.4 to manage routes, along with its modern data loader/action architecture, enabling declarative data fetching, mutation, and revalidation via fetchers.</li>
                    <li>Redux Toolkit (RTK) is used for managing the shopping cart state.</li>
                    <li>Language switching is handled in real-time via the Context API, ensuring smooth multilingual UX throughout the session.</li>
                    </ul>
                    <p>This project demonstrates practical, real-world application of advanced React patterns, multilingual support, and thoughtful UX tailored for local and international business needs.</p>
                    <p>You can explore the full experience via the Live Demo link provided in the sidebar.</p>',
                'client' => 'Confidential',
                'demo_link' => 'https://mernapp.avamafarin.ir',
                'category' => 'Web App',
                'primary_image' => 'storage/images/projects/pizzabottura/pizzabottura-1.webp',
                'order' => 4,
                'role' => 'Full-Stack Web Developer',
                'start_date' => '2023-03-25',
            ],
            [
                'title' => 'Wooden Cottage Booking',
                'summary' => 'Next.js 14 fullstack booking site using App Router and modern patterns.',
                'description' => '<p>The Wooden Cottage Booking Website is the official platform for reserving rooms at a luxury family-run retreat, located in a lush and peaceful area. This project complements the previously introduced admin panel (developed as a separate SPA) by providing the main public-facing booking interface, where SEO, performance, and user experience were essential priorities.</p>
                    <p>To meet these needs, the entire application was built using Next.js 14, leveraging the App Router architecture—a modern routing approach introduced in Next.js 13 and officially recommended by Vercel for its superior speed, flexibility, and interactivity compared to the legacy Page Router.</p>
                    <p>This fullstack application fully embraces the React Server Component paradigm, which offers enhanced performance, security, and a more seamless developer experience. The approach is now also being widely adopted in React 19, further validating its relevance and maturity.</p>
                    <h3>Key technical highlights include:</h3>
                    <ul>
                    <li>Styling with Tailwind CSS, ensuring fully responsive design across all devices.</li>
                    <li>SSG and dynamic rendering handled thoughtfully based on use case. For example, the About Us page is rendered statically using SSG + ISR, since it changes infrequently.</li>
                    <li>Data fetching is managed using React Server Components, while Server Actions are used for mutations—following the latest patterns natively supported in the App Router structure.</li>
                    <li>Suspense is used for loading states in DB-interactive components, improving UX by keeping transitions smooth and responsive.</li>
                    <li>While the project was built before PPR (Partial Pre-Rendering) became available for production, it is well-prepared to adopt it in future updates.</li>
                    <li>Advanced React hooks like <code>useTransition</code> and <code>useOptimistic</code> have been applied to deliver fast, intuitive feedback during user interactions.</li>
                    <li>Authentication is handled via Google Auth, with Next.js Middleware used to protect private routes and manage session access securely.</li>
                    </ul>
                    <p>Importantly, no third-party state management libraries like React Query were used. The project adheres strictly to Next.js-native solutions, embracing modern, framework-aligned practices to ensure maintainability, simplicity, and performance.</p>
                    <p>This project is fully deployed and hosted on Vercel, and you can explore the live demo via the link provided in the adjacent demo section.</p>',
                'client' => 'Wooden Cottage',
                'demo_link' => 'https://avamafarin-nextjs-app.vercel.app',
                'category' => 'Full-Stack Web App',
                'primary_image' => 'storage/images/projects/wooden/wooden-1.webp',
                'order' => 6,
                'role' => 'Full-Stack Web Developer',
                'start_date' => '2024-07-22',
            ],
            [
                'title' => 'Wooden Cottage Admin Panel',
                'summary' => 'React SPA for cottage management with strong UX and clean architecture.',
                'description' => '<p>The Wooden Cottage Admin Panel is a Single Page Application (SPA) developed entirely with React 18, specifically designed for internal use within a boutique family-owned hospitality business. This business offers luxury wooden cottages in a scenic, nature-rich area, and the admin panel was built to manage operations efficiently with high interactivity in mind—without the need for SEO indexing.</p>
                    <p>The entire UI was styled from scratch using styled-components—no UI libraries or pre-built templates were used. The design targets medium to large screen sizes, as the panel is primarily used on desktops within the organization. Given the internal nature of the app, it was purposefully not designed mobile-first, although responsiveness on larger viewports was carefully considered.</p>
                    <h3>From a technical perspective, this project demonstrates best practices in React architecture:</h3>
                    <ul>
                    <li>Routing is handled via React Router.</li>
                    <li>Global state management is implemented using React Query, chosen for its robust handling of remote state and features like caching, automatic background updates, and pre-fetching—the latter is particularly used in pagination to enhance the user experience.</li>
                    <li>All CRUD operations are modularized through custom hooks, promoting a clean and reusable codebase.</li>
                    <li>Form management is powered by React Hook Form, allowing fine-grained control over validation and business logic enforcement at the client level.</li>
                    </ul>
                    <p>Many interactions, such as confirmations and alerts, are handled via modals built with React Portals. These modals are built using the Compound Component Pattern, enabling high flexibility and customization. This same modular, scalable approach has been applied to data tables, per-record action menus, and other interactive UI elements.</p>
                    <p>To ensure clean separation of concerns and improve maintainability, Render Props Pattern was also used in table components, enhancing logic abstraction and reusability.</p>
                    <p>Data visualization is delivered through Recharts, providing an engaging and animated user experience when working with graphs and reports.</p>
                    <p>The theme switcher (Dark Mode) is managed using the Context API, offering global state management with a clean and simple implementation, ideal for a lightweight internal application.</p>
                    <p>In summary, this project showcases a wide range of React fundamentals and advanced concepts, all applied with precision and attention to best practices in architecture, performance, and user experience. It serves as a strong demonstration of my capabilities in React development, design thinking, and component-based engineering.</p>
                    <p>You can try the live demo by clicking the Demo Link on the right, using the credentials below:<br>
                    • Username: usertester@test.com<br>
                    • Password: password123</p>',
                'client' => 'Wooden Cottage',
                'demo_link' => 'https://reactapp.avamafarin.ir',
                'category' => 'SPA Admin Panel',
                'primary_image' => 'storage/images/projects/wooden-admin/wooden-admin-1.webp',
                'order' => 5,
                'role' => 'Full-Stack Web Developer',
                'start_date' => '2024-01-09',
            ],
        ];
        $projectStackRelation = [
            [1, 2, 3, 4, 5],
            [1, 2, 6],
            [6, 7, 11, 12, 13,],
            [8, 2, 9, 6, 10],
            [11, 13, 3, 12, 5, 16],
            [14, 3, 5, 18],
            [3, 15, 17, 18],
        ];
        $this->fillDB($projects, Project::class, true, 'stacks', $projectStackRelation);


        // Fill Project Images
        $projectImages = [
            ['project_id' => 1, 'image' => 'storage/images/projects/portfolio/portfolio-2.webp', 'alt' => 'portfolio image 2', 'order' => 1],
            ['project_id' => 1, 'image' => 'storage/images/projects/portfolio/portfolio-3.webp', 'alt' => 'portfolio image 3', 'order' => 2],
            ['project_id' => 1, 'image' => 'storage/images/projects/portfolio/portfolio-4.webp', 'alt' => 'portfolio image 4', 'order' => 3],
            ['project_id' => 1, 'image' => 'storage/images/projects/portfolio/portfolio-5.webp', 'alt' => 'portfolio image 5', 'order' => 4],

            ['project_id' => 2, 'image' => 'storage/images/projects/atryad/atryad-2.webp', 'alt' => 'atryad image 2', 'order' => 1],
            ['project_id' => 2, 'image' => 'storage/images/projects/atryad/atryad-3.webp', 'alt' => 'atryad image 3', 'order' => 2],
            ['project_id' => 2, 'image' => 'storage/images/projects/atryad/atryad-4.webp', 'alt' => 'atryad image 4', 'order' => 3],
            ['project_id' => 2, 'image' => 'storage/images/projects/atryad/atryad-5.webp', 'alt' => 'atryad image 5', 'order' => 4],

            ['project_id' => 3, 'image' => 'storage/images/projects/outworks/outworks-2.webp', 'alt' => 'outworks image 2', 'order' => 1],
            ['project_id' => 3, 'image' => 'storage/images/projects/outworks/outworks-3.webp', 'alt' => 'outworks image 3', 'order' => 2],
            ['project_id' => 3, 'image' => 'storage/images/projects/outworks/outworks-4.webp', 'alt' => 'outworks image 4', 'order' => 3],
            ['project_id' => 3, 'image' => 'storage/images/projects/outworks/outworks-5.webp', 'alt' => 'outworks image 5', 'order' => 4],

            ['project_id' => 4, 'image' => 'storage/images/projects/oudwood/oudwood-2.webp', 'alt' => 'oudwood image 2', 'order' => 1],
            ['project_id' => 4, 'image' => 'storage/images/projects/oudwood/oudwood-3.webp', 'alt' => 'oudwood image 3', 'order' => 2],
            ['project_id' => 4, 'image' => 'storage/images/projects/oudwood/oudwood-4.webp', 'alt' => 'oudwood image 4', 'order' => 3],
            ['project_id' => 4, 'image' => 'storage/images/projects/oudwood/oudwood-5.webp', 'alt' => 'oudwood image 5', 'order' => 4],

            ['project_id' => 5, 'image' => 'storage/images/projects/pizzabottura/pizzabottura-2.webp', 'alt' => 'pizzabottura image 2', 'order' => 1],
            ['project_id' => 5, 'image' => 'storage/images/projects/pizzabottura/pizzabottura-3.webp', 'alt' => 'pizzabottura image 3', 'order' => 2],
            ['project_id' => 5, 'image' => 'storage/images/projects/pizzabottura/pizzabottura-4.webp', 'alt' => 'pizzabottura image 4', 'order' => 3],
            ['project_id' => 5, 'image' => 'storage/images/projects/pizzabottura/pizzabottura-5.webp', 'alt' => 'pizzabottura image 5', 'order' => 4],

            ['project_id' => 6, 'image' => 'storage/images/projects/wooden/wooden-2.webp', 'alt' => 'wooden image 2', 'order' => 1],
            ['project_id' => 6, 'image' => 'storage/images/projects/wooden/wooden-3.webp', 'alt' => 'wooden image 3', 'order' => 2],
            ['project_id' => 6, 'image' => 'storage/images/projects/wooden/wooden-4.webp', 'alt' => 'wooden image 4', 'order' => 3],
            ['project_id' => 6, 'image' => 'storage/images/projects/wooden/wooden-5.webp', 'alt' => 'wooden image 5', 'order' => 4],

            ['project_id' => 7, 'image' => 'storage/images/projects/wooden-admin/wooden-admin-2.webp', 'alt' => 'wooden-admin image 2', 'order' => 1],
            ['project_id' => 7, 'image' => 'storage/images/projects/wooden-admin/wooden-admin-3.webp', 'alt' => 'wooden-admin image 3', 'order' => 2],
            ['project_id' => 7, 'image' => 'storage/images/projects/wooden-admin/wooden-admin-4.webp', 'alt' => 'wooden-admin image 4', 'order' => 3],
            ['project_id' => 7, 'image' => 'storage/images/projects/wooden-admin/wooden-admin-5.webp', 'alt' => 'wooden-admin image 5', 'order' => 4],

        ];
        $this->fillDB($projectImages, ProjectImage::class);

        // Fill Skills
        $skills = [
            ['title' => 'Programming Languages', 'description' => 'PHP, JavaScript, Python, C#.Net'],
            ['title' => 'Web Frameworks', 'description' => 'Laravel, Express, Next.js'],
            ['title' => 'JavaScript Libraries', 'description' => 'React (Router, Query, RTK, Redux, Context API)'],
            ['title' => 'UI Libraries/Frameworks', 'description' => 'Bootstrap, Tailwind CSS'],
            ['title' => 'Databases', 'description' => 'MSSQL, MySQL, PostgreSQL, MongoDB'],
            ['title' => 'Architecture', 'description' => 'MVC, RESTFull API, GraphQL'],
            ['title' => 'Others', 'description' => 'OOP, Design Patterns, PHPUnit Test, Git, SASS, Postman, WebSocket, JWT, OAuth2, Webpack, Vite'],
        ];
        $this->fillDB($skills, Skill::class);

        // Fill AboutMe
        $aboutMe = [
            [
                'title' => 'Amirsaeed, Full-Stack Developer',
                'summary' => "<p class='mb-4 leading-relaxed'>
                I'm a passionate Full-Stack Web Developer with over 13 years of
                software development experience and 5+ years specializing in web
                applications. Proficient in JavaScript, PHP, and Python, with
                expertise in building scalable applications using modern frameworks
                such as React, Laravel, and Next.js. Adept at improving performance,
                enhancing user experience, and implementing efficient back-end
                architectures. Passionate about problem-solving, agile
                methodologies, and collaborating with cross-functional teams to
                deliver high-quality products.
            </p>
            <p class='mb-8 leading-relaxed'>
                I specialize in creating responsive, user-friendly interfaces and
                robust backend systems.
            </p>",
                'location' => 'Tehran/Iran',
                'years_of_experience' => 13,
                'language' => json_encode(['Persian' => 'Native', 'English' => 'B2'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                'currently_learning' => 'TypeScript',
                'linkedin_url' => 'https://linkedin.com/in/amirsaeed-skomjani',
                'github_url' => 'https://github.com/amirsaeedsadeghi',
                'portfolio_image' => 'storage/images/users/profile.webp',
            ]
        ];
        $this->fillDB($aboutMe, AboutMe::class);
    }

    /**
     * @param array $items
     * @param class-string<Model> $model
     * @param bool $hasRelation
     * @param string|null $relationMethod
     * @param array $relationData
     * @return void
     */
    public function fillDB(array $items, string $model, bool $hasRelation = false, ?string $relationMethod = null, array $relationData = []): void
    {
        if (!$hasRelation) {
            foreach ($items as $item) {
                $model::create($item);
            }
        } else if ($hasRelation && $relationMethod !== null) {
            $index = 0;
            foreach ($items as $item) {
                $firstModel = $model::create($item);
                $firstModel->{$relationMethod}()->attach($relationData[$index]);
                $index++;
            }
        }
    }
}
