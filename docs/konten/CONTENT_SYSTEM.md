# 📝 Dual Content System

## 🎯 Overview

Sistem ini menggunakan dua jenis konten yang berbeda untuk memenuhi berbagai kebutuhan:

1. **General Posts** - Konten cepat dan fleksibel berdasarkan role
2. **Program-Based Content** - Konten terstruktur dengan dokumentasi lengkap

## Content Type 1: General Posts

### Structure
```php
Schema::create('general_posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->string('category'); // osis, ekskul, guru, wali_kelas, admin
    $table->string('subcategory'); // news, announcement, event, achievement
    $table->string('status'); // draft, review, approved, published
    $table->json('tags');
    $table->json('attachments');
    $table->string('author_role');
    $table->unsignedBigInteger('author_id');
    $table->boolean('is_featured');
    $table->boolean('is_pinned');
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

### Role-Based Categories
```javascript
const roleContentCategories = {
  admin: ['announcement', 'policy', 'news', 'achievement'],
  staff: ['announcement', 'news', 'event', 'achievement'],
  guru: ['academic_news', 'class_activity', 'achievement'],
  wali_kelas: ['class_news', 'student_achievement', 'parent_communication'],
  siswa_osis: ['osis_news', 'osis_event', 'osis_achievement'],
  siswa_ekskul: ['ekskul_news', 'ekskul_event', 'ekskul_achievement']
};
```

### Workflow
```
Draft → Review → Approved → Published
  ↑        ↑        ↑         ↑
Siswa   Wali    Staff    Admin
```

## 🏗️ Content Type 2: Program-Based Content

### Program Structure
```php
Schema::create('programs', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->string('category'); // kesiswaan, akademik, karakter, ekskul
    $table->string('status'); // planning, active, completed, cancelled
    $table->date('start_date');
    $table->date('end_date');
    $table->json('objectives');
    $table->json('target_audience');
    $table->string('responsible_role');
    $table->unsignedBigInteger('responsible_id');
    $table->json('components');
    $table->json('completion_status');
    $table->timestamps();
});
```

### Program Components
```javascript
const programComponentTypes = {
  agenda: {
    name: 'Agenda Program',
    description: 'Jadwal dan timeline program',
    required: true,
    fields: ['timeline', 'activities', 'milestones', 'deadlines']
  },
  proposal: {
    name: 'Proposal Program',
    description: 'Dokumen proposal lengkap',
    required: true,
    fields: ['background', 'objectives', 'methodology', 'budget', 'timeline']
  },
  internal_report: {
    name: 'Laporan Internal',
    description: 'Laporan untuk internal sekolah',
    required: true,
    fields: ['execution_summary', 'challenges', 'lessons_learned', 'recommendations']
  },
  public_report: {
    name: 'Laporan Publik',
    description: 'Laporan yang ditampilkan di landing page',
    required: true,
    fields: ['summary', 'achievements', 'impact', 'gallery', 'testimonials']
  }
};
```

### Program Workflow
```
Planning → Active → Components → Review → Completed
    ↑        ↑         ↑          ↑         ↑
  Admin   Admin    Role-based   Staff    Admin
```

## Content Workflow

### General Posts Workflow
1. **Creation** - User creates content based on role
2. **Review** - Content goes to review queue
3. **Approval** - Staff/Admin approves content
4. **Publishing** - Content published to landing page

### Program-Based Workflow
1. **Planning** - Admin creates program structure
2. **Active** - Program becomes active
3. **Components** - Role-based users fill components
4. **Review** - Staff reviews completed components
5. **Completion** - Program marked as completed

## 📊 Completion Tracking

### Program Completion Service
```javascript
class ProgramCompletionService {
  async checkProgramCompletion(programId) {
    const program = await Program.find(programId);
    const components = await ProgramComponent.where('program_id', programId).get();
    
    const requiredComponents = components.filter(c => c.is_required);
    const completedComponents = requiredComponents.filter(c => c.is_completed);
    
    const completionPercentage = (completedComponents.length / requiredComponents.length) * 100;
    const isComplete = completionPercentage === 100;
    
    return {
      completionPercentage,
      isComplete,
      missingComponents: requiredComponents.filter(c => !c.is_completed)
    };
  }
}
```

## Report Generation

### Program Report Service
```javascript
class ProgramReportService {
  async generateCompleteReport(programId) {
    const program = await Program.find(programId);
    const components = await ProgramComponent.where('program_id', programId).get();
    
    return {
      program: {
        name: program.name,
        description: program.description,
        status: program.status,
        objectives: program.objectives
      },
      components: {
        agenda: components.find(c => c.component_type === 'agenda'),
        proposal: components.find(c => c.component_type === 'proposal'),
        internalReport: components.find(c => c.component_type === 'internal_report'),
        publicReport: components.find(c => c.component_type === 'public_report')
      },
      analytics: {
        completionPercentage: program.completion_percentage,
        isComplete: program.status === 'completed',
        timeline: await this.generateTimeline(program),
        impact: await this.calculateImpact(program)
      }
    };
  }
}
```

## 🎯 Benefits

### General Posts
- ✅ **Quick Updates** - Informasi cepat dan update
- ✅ **Role-Based** - Sesuai dengan kebutuhan masing-masing role
- ✅ **Flexible** - Mudah dibuat dan dimodifikasi

### Program-Based Content
- ✅ **Structured** - Dokumentasi yang terstruktur
- ✅ **Accountable** - Tracking progress dan completion
- ✅ **Comprehensive** - Laporan yang lengkap dan detail
