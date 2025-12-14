<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Interview;
use App\Models\JobOffer;
use App\Models\JobPosting;
use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
    /**
     * Seed the recruitment subsystem with realistic data.
     */
    public function run(): void
    {
        // Create Job Postings with specific data for better demonstration
        $jobPostings = [
            JobPosting::firstOrCreate(
                ['title' => 'Senior Software Engineer'],
                [
                    'description' => "We are looking for a Senior Software Engineer to join our dynamic team.\n\nResponsibilities:\n- Design and develop scalable software solutions\n- Mentor junior developers\n- Participate in code reviews and architectural decisions\n- Collaborate with cross-functional teams\n\nRequirements:\n- 5+ years of software development experience\n- Proficiency in PHP, Laravel, and modern JavaScript frameworks\n- Strong problem-solving skills\n- Excellent communication abilities",
                    'location' => 'New York, NY',
                    'employment_type' => 'Full-time',
                    'salary_min' => 90000.00,
                    'salary_max' => 130000.00,
                    'is_active' => true,
                    'posted_at' => now()->subDays(30),
                ]
            ),
            JobPosting::firstOrCreate(
                ['title' => 'Junior Frontend Developer'],
                [
                    'description' => "Exciting opportunity for a Junior Frontend Developer to grow with our team.\n\nResponsibilities:\n- Build responsive user interfaces using React/Vue\n- Collaborate with designers and backend developers\n- Write clean, maintainable code\n- Learn and adopt best practices\n\nRequirements:\n- 1-2 years of frontend development experience\n- Knowledge of HTML, CSS, JavaScript\n- Familiarity with modern frontend frameworks\n- Eager to learn and grow",
                    'location' => 'Remote',
                    'employment_type' => 'Full-time',
                    'salary_min' => 45000.00,
                    'salary_max' => 65000.00,
                    'is_active' => true,
                    'posted_at' => now()->subDays(14),
                ]
            ),
            JobPosting::firstOrCreate(
                ['title' => 'DevOps Engineer'],
                [
                    'description' => "Join our infrastructure team as a DevOps Engineer.\n\nResponsibilities:\n- Manage and improve CI/CD pipelines\n- Monitor and maintain cloud infrastructure\n- Automate deployment processes\n- Implement security best practices\n\nRequirements:\n- 3+ years of DevOps experience\n- Expertise in AWS/GCP/Azure\n- Proficiency in Docker and Kubernetes\n- Experience with Terraform or similar IaC tools",
                    'location' => 'San Francisco, CA',
                    'employment_type' => 'Full-time',
                    'salary_min' => 100000.00,
                    'salary_max' => 150000.00,
                    'is_active' => true,
                    'posted_at' => now()->subDays(7),
                ]
            ),
            JobPosting::firstOrCreate(
                ['title' => 'Product Manager'],
                [
                    'description' => "We're seeking an experienced Product Manager to lead our product strategy.\n\nResponsibilities:\n- Define product vision and roadmap\n- Gather and prioritize product requirements\n- Work closely with engineering and design teams\n- Analyze market trends and competition\n\nRequirements:\n- 4+ years of product management experience\n- Strong analytical and communication skills\n- Experience with Agile methodologies\n- Technical background preferred",
                    'location' => 'Chicago, IL',
                    'employment_type' => 'Full-time',
                    'salary_min' => 85000.00,
                    'salary_max' => 120000.00,
                    'is_active' => true,
                    'posted_at' => now()->subDays(21),
                ]
            ),
            JobPosting::firstOrCreate(
                ['title' => 'Marketing Intern'],
                [
                    'description' => "Summer internship opportunity in our Marketing department.\n\nResponsibilities:\n- Assist with social media campaigns\n- Help create marketing materials\n- Conduct market research\n- Support the marketing team in daily tasks\n\nRequirements:\n- Currently pursuing a degree in Marketing or related field\n- Strong written communication skills\n- Creativity and attention to detail\n- Available for 3-month internship",
                    'location' => 'Boston, MA',
                    'employment_type' => 'Internship',
                    'salary_min' => 18000.00,
                    'salary_max' => 24000.00,
                    'is_active' => false,
                    'posted_at' => now()->subDays(60),
                ]
            ),
            JobPosting::firstOrCreate(
                ['title' => 'Customer Support Specialist'],
                [
                    'description' => "Join our customer-facing team as a Support Specialist.\n\nResponsibilities:\n- Handle customer inquiries via phone, email, and chat\n- Troubleshoot technical issues\n- Document common problems and solutions\n- Escalate complex issues to appropriate teams\n\nRequirements:\n- 2+ years of customer service experience\n- Excellent communication skills\n- Patience and empathy\n- Technical aptitude",
                    'location' => 'Austin, TX',
                    'employment_type' => 'Full-time',
                    'salary_min' => 40000.00,
                    'salary_max' => 55000.00,
                    'is_active' => true,
                    'posted_at' => now()->subDays(5),
                ]
            ),
        ];

        // Create Candidates for different job postings with varied statuses
        $candidates = [
            // Candidates for Senior Software Engineer
            Candidate::firstOrCreate(
                ['email' => 'john.smith@email.com'],
                [
                    'job_posting_id' => $jobPostings[0]->id,
                    'name' => 'John Smith',
                    'phone' => '+1-555-123-4567',
                    'resume_url' => 'resumes/john-smith-resume.pdf',
                    'status' => 'interviewed',
                    'notes' => 'Strong technical skills, 7 years of experience. Very promising candidate.',
                ]
            ),
            Candidate::firstOrCreate(
                ['email' => 'sarah.johnson@email.com'],
                [
                    'job_posting_id' => $jobPostings[0]->id,
                    'name' => 'Sarah Johnson',
                    'phone' => '+1-555-234-5678',
                    'resume_url' => 'resumes/sarah-johnson-resume.pdf',
                    'status' => 'interview_scheduled',
                    'notes' => 'Excellent portfolio, comes from a FAANG company.',
                ]
            ),
            Candidate::firstOrCreate(
                ['email' => 'mike.wilson@email.com'],
                [
                    'job_posting_id' => $jobPostings[0]->id,
                    'name' => 'Mike Wilson',
                    'phone' => '+1-555-345-6789',
                    'resume_url' => 'resumes/mike-wilson-resume.pdf',
                    'status' => 'reviewing',
                    'notes' => 'Interesting background in AI/ML.',
                ]
            ),

            // Candidates for Junior Frontend Developer
            Candidate::firstOrCreate(
                ['email' => 'emily.chen@email.com'],
                [
                    'job_posting_id' => $jobPostings[1]->id,
                    'name' => 'Emily Chen',
                    'phone' => '+1-555-456-7890',
                    'resume_url' => 'resumes/emily-chen-resume.pdf',
                    'status' => 'interviewed',
                    'notes' => 'Recent bootcamp graduate with impressive projects.',
                ]
            ),
            Candidate::firstOrCreate(
                ['email' => 'alex.rivera@email.com'],
                [
                    'job_posting_id' => $jobPostings[1]->id,
                    'name' => 'Alex Rivera',
                    'phone' => '+1-555-567-8901',
                    'resume_url' => 'resumes/alex-rivera-resume.pdf',
                    'status' => 'new',
                    'notes' => null,
                ]
            ),

            // Candidates for DevOps Engineer
            Candidate::firstOrCreate(
                ['email' => 'david.kumar@email.com'],
                [
                    'job_posting_id' => $jobPostings[2]->id,
                    'name' => 'David Kumar',
                    'phone' => '+1-555-678-9012',
                    'resume_url' => 'resumes/david-kumar-resume.pdf',
                    'status' => 'interview_scheduled',
                    'notes' => 'AWS certified, strong Kubernetes experience.',
                ]
            ),
            Candidate::firstOrCreate(
                ['email' => 'lisa.patel@email.com'],
                [
                    'job_posting_id' => $jobPostings[2]->id,
                    'name' => 'Lisa Patel',
                    'phone' => '+1-555-789-0123',
                    'resume_url' => 'resumes/lisa-patel-resume.pdf',
                    'status' => 'reviewing',
                    'notes' => 'Experience with major cloud providers.',
                ]
            ),

            // Candidates for Product Manager
            Candidate::firstOrCreate(
                ['email' => 'james.brown@email.com'],
                [
                    'job_posting_id' => $jobPostings[3]->id,
                    'name' => 'James Brown',
                    'phone' => '+1-555-890-1234',
                    'resume_url' => 'resumes/james-brown-resume.pdf',
                    'status' => 'interviewed',
                    'notes' => 'Great presentation during interview. Strong leadership skills.',
                ]
            ),

            // Candidates for Customer Support
            Candidate::firstOrCreate(
                ['email' => 'maria.garcia@email.com'],
                [
                    'job_posting_id' => $jobPostings[5]->id,
                    'name' => 'Maria Garcia',
                    'phone' => '+1-555-901-2345',
                    'resume_url' => 'resumes/maria-garcia-resume.pdf',
                    'status' => 'new',
                    'notes' => 'Bilingual English/Spanish.',
                ]
            ),
            Candidate::firstOrCreate(
                ['email' => 'tom.anderson@email.com'],
                [
                    'job_posting_id' => $jobPostings[5]->id,
                    'name' => 'Tom Anderson',
                    'phone' => '+1-555-012-3456',
                    'resume_url' => 'resumes/tom-anderson-resume.pdf',
                    'status' => 'reviewing',
                    'notes' => '5 years of customer service experience.',
                ]
            ),
        ];

        // Create Interviews for candidates with interview_scheduled or interviewed status
        $interviews = [
            // Interview for John Smith (interviewed)
            Interview::firstOrCreate(
                ['candidate_id' => $candidates[0]->id, 'scheduled_at' => now()->subDays(5)->setTime(10, 0)],
                [
                    'location' => '123 Company HQ, New York, NY',
                    'mode' => 'in-person',
                    'notes' => 'Technical interview completed. Candidate demonstrated strong problem-solving skills. Recommended for offer.',
                ]
            ),

            // Interview for Sarah Johnson (interview_scheduled)
            Interview::firstOrCreate(
                ['candidate_id' => $candidates[1]->id, 'scheduled_at' => now()->addDays(3)->setTime(14, 0)],
                [
                    'location' => 'Virtual Meeting',
                    'mode' => 'video',
                    'notes' => 'First round technical interview via Zoom.',
                ]
            ),

            // Interview for Emily Chen (interviewed)
            Interview::firstOrCreate(
                ['candidate_id' => $candidates[3]->id, 'scheduled_at' => now()->subDays(3)->setTime(11, 0)],
                [
                    'location' => 'Virtual Meeting',
                    'mode' => 'video',
                    'notes' => 'Portfolio review went well. Enthusiastic about learning.',
                ]
            ),

            // Interview for David Kumar (interview_scheduled)
            Interview::firstOrCreate(
                ['candidate_id' => $candidates[5]->id, 'scheduled_at' => now()->addDays(5)->setTime(9, 0)],
                [
                    'location' => '456 Tech Center, San Francisco, CA',
                    'mode' => 'in-person',
                    'notes' => 'Technical assessment and team fit interview.',
                ]
            ),

            // Interview for James Brown (interviewed) - 2 interviews
            Interview::firstOrCreate(
                ['candidate_id' => $candidates[7]->id, 'scheduled_at' => now()->subDays(10)->setTime(13, 0)],
                [
                    'location' => 'Virtual Meeting',
                    'mode' => 'video',
                    'notes' => 'Initial screening - Very articulate and experienced.',
                ]
            ),
            Interview::firstOrCreate(
                ['candidate_id' => $candidates[7]->id, 'scheduled_at' => now()->subDays(2)->setTime(15, 0)],
                [
                    'location' => '789 Chicago Office, Chicago, IL',
                    'mode' => 'in-person',
                    'notes' => 'Final round with leadership team. Excellent candidate.',
                ]
            ),
        ];

        // Create Job Offers for top candidates
        $year = now()->year;

        // Job offer for John Smith (Senior Software Engineer)
        JobOffer::firstOrCreate(
            ['candidate_id' => $candidates[0]->id],
            [
                'job_posting_id' => $jobPostings[0]->id,
                'offer_number' => "JO-{$year}-001",
                'salary' => 115000.00,
                'start_date' => now()->addDays(30),
                'expires_at' => now()->addDays(14),
                'status' => 'sent',
                'pdf_path' => 'job-offers/john-smith-offer.pdf',
                'sent_at' => now()->subDays(1),
            ]
        );

        // Job offer for Emily Chen (Junior Frontend Developer)
        JobOffer::firstOrCreate(
            ['candidate_id' => $candidates[3]->id],
            [
                'job_posting_id' => $jobPostings[1]->id,
                'offer_number' => "JO-{$year}-002",
                'salary' => 55000.00,
                'start_date' => now()->addDays(45),
                'expires_at' => now()->addDays(10),
                'status' => 'draft',
                'pdf_path' => null,
                'sent_at' => null,
            ]
        );

        // Job offer for James Brown (Product Manager)
        JobOffer::firstOrCreate(
            ['candidate_id' => $candidates[7]->id],
            [
                'job_posting_id' => $jobPostings[3]->id,
                'offer_number' => "JO-{$year}-003",
                'salary' => 105000.00,
                'start_date' => now()->addDays(60),
                'expires_at' => now()->addDays(21),
                'status' => 'sent',
                'pdf_path' => 'job-offers/james-brown-offer.pdf',
                'sent_at' => now()->subHours(12),
            ]
        );

        $this->command->info('Recruitment data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('  - '.count($jobPostings).' job postings');
        $this->command->info('  - '.count($candidates).' candidates');
        $this->command->info('  - '.count($interviews).' interviews');
        $this->command->info('  - 3 job offers');
    }
}
