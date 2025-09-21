#!/bin/bash

# SISKA Database Seeder Script
# This script will seed the database with comprehensive sample data

echo "🌱 Starting SISKA Database Seeding Process..."

# Change to backend directory
cd /opt/kesiswaan/backend

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Please run this script from the backend directory."
    exit 1
fi

echo "📋 Current directory: $(pwd)"

# Check if database is accessible
echo "🔍 Checking database connection..."
php artisan migrate:status > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "❌ Error: Cannot connect to database. Please check your database configuration."
    exit 1
fi

echo "✅ Database connection successful"

# Ask for confirmation
echo ""
echo "⚠️  WARNING: This will seed the database with sample data."
echo "   This action will add thousands of records to your database."
echo "   Make sure you have a backup if needed."
echo ""
read -p "Do you want to continue? (y/N): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Seeding cancelled by user."
    exit 1
fi

# Run migrations first (in case there are new migrations)
echo "🔄 Running migrations..."
php artisan migrate --force

# Clear cache
echo "🧹 Clearing cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run the seeder
echo "🌱 Running database seeder..."
php artisan db:seed --force

# Check if seeding was successful
if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Database seeding completed successfully!"
    echo ""
    echo "📊 Sample data has been added:"
    echo "   - Users and roles"
    echo "   - Academic years and classes"
    echo "   - Students (hundreds of records)"
    echo "   - Subjects and teachers"
    echo "   - Attendance records (last 30 days)"
    echo "   - Credit points and character assessments"
    echo "   - OSIS activities and extracurriculars"
    echo "   - Gallery and content management"
    echo "   - Permissions and access rights"
    echo ""
    echo "🎯 You can now test all features with realistic data!"
    echo ""
    echo "🔑 Default login credentials:"
    echo "   Email: admin@siska.com"
    echo "   Password: password"
    echo ""
else
    echo "❌ Database seeding failed. Please check the error messages above."
    exit 1
fi
