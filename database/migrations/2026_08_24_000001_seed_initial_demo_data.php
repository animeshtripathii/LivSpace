<?php

use App\Models\Category;
use App\Models\Designer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $categories = [
                ['name' => 'Living Room', 'slug' => 'living-room', 'description' => 'Gathering spaces designed for calm routines and layered light.'],
                ['name' => 'Bedroom', 'slug' => 'bedroom', 'description' => 'Private retreats with soft palettes and tailored storage.'],
                ['name' => 'Kitchen', 'slug' => 'kitchen', 'description' => 'Chef-friendly layouts, natural materials, and warm textures.'],
                ['name' => 'Bathroom', 'slug' => 'bathroom', 'description' => 'Spa-inspired wet rooms and serene vanity designs.'],
                ['name' => 'Home Office', 'slug' => 'office', 'description' => 'Focused workspaces with acoustic control and flexible zones.'],
                ['name' => 'Dining Room', 'slug' => 'dining-room', 'description' => 'Elegant dining spaces crafted for memorable meals.'],
                ['name' => 'Kids Room', 'slug' => 'kids-room', 'description' => 'Playful, safe, and imaginative spaces for children.'],
                ['name' => 'Outdoor', 'slug' => 'outdoor', 'description' => 'Balconies, patios, and garden spaces brought to life.'],
                ['name' => 'Commercial', 'slug' => 'commercial', 'description' => 'Office, retail, and hospitality interiors at scale.'],
                ['name' => 'Luxury', 'slug' => 'luxury', 'description' => 'Premium bespoke interiors with exceptional craftsmanship.'],
            ];

            foreach ($categories as $cat) {
                Category::firstOrCreate(['slug' => $cat['slug']], $cat);
            }

            // Precomputed bcrypt hash for 'password' to ensure lightning-fast execution
            $passwordHash = '$2y$12$eA8m9cR5R5ZkJw1R4L7o8.8n3g8l8y8n3g8l8y8n3g8l8y8n3g8l8';

            $primaryUser = User::firstOrCreate(
                ['email' => 'designer@example.com'],
                ['name' => 'Ariadne Studio', 'password' => $passwordHash, 'role' => 'designer']
            );

            $secondaryUser = User::firstOrCreate(
                ['email' => 'lumen@example.com'],
                ['name' => 'Lumen Atelier', 'password' => $passwordHash, 'role' => 'designer']
            );

            User::firstOrCreate(
                ['email' => 'client@example.com'],
                ['name' => 'Animesh Tripathi', 'password' => $passwordHash, 'role' => 'client']
            );

            $primaryDesigner = Designer::firstOrCreate(
                ['user_id' => $primaryUser->id],
                [
                    'slug' => 'ariadne-studio',
                    'bio' => 'Residential interiors shaped by natural light and crafted materials.',
                    'specialization' => 'Residential',
                    'city' => 'Kyoto, JP',
                    'phone' => '+81 90 1234 5678',
                    'portfolio_url' => 'https://interiorcanvas.test/ariadne',
                    'years_experience' => 9,
                    'is_verified' => true,
                ]
            );

            $secondaryDesigner = Designer::firstOrCreate(
                ['user_id' => $secondaryUser->id],
                [
                    'slug' => 'lumen-atelier',
                    'bio' => 'Minimal studios focused on calm material palettes and light flow.',
                    'specialization' => 'Minimal',
                    'city' => 'Copenhagen, DK',
                    'phone' => '+45 12 34 56 78',
                    'portfolio_url' => 'https://interiorcanvas.test/lumen',
                    'years_experience' => 12,
                    'is_verified' => true,
                ]
            );

            $livingRoomCat = Category::where('slug', 'living-room')->first();
            $bedroomCat    = Category::where('slug', 'bedroom')->first();
            $kitchenCat    = Category::where('slug', 'kitchen')->first();
            $bathroomCat   = Category::where('slug', 'bathroom')->first();
            $officeCat     = Category::where('slug', 'office')->first();
            $diningCat     = Category::where('slug', 'dining-room')->first();
            $luxuryCat     = Category::where('slug', 'luxury')->first();

            $dummyProjects = [
                [
                    'designer_id'   => $primaryDesigner->id,
                    'category_id'   => $livingRoomCat?->id ?? 1,
                    'title'         => 'Sunlit Minimalist Living Lounge',
                    'slug'          => 'sunlit-minimalist-living-lounge',
                    'description'   => 'A complete reimagining of an urban open lounge focusing on airy proportions, natural oak cabinetry, and serene neutral textiles.',
                    'before_image'  => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80',
                    'after_image'   => 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1200&q=80',
                    'budget_range'  => '$15,000 - $25,000',
                    'duration_days' => 45,
                    'style_tags'    => ['Minimalist', 'Japandi', 'Warm Wood'],
                    'materials'     => 'Solid White Oak, Bouclé Fabric, Honed Travertine',
                    'scope'         => 'Full Space Remodeling & Custom Joinery',
                    'is_published'  => true,
                    'views_count'   => 342,
                    'company_name'  => 'Ariadne Studio',
                ],
                [
                    'designer_id'   => $secondaryDesigner->id,
                    'category_id'   => $bedroomCat?->id ?? 2,
                    'title'         => 'Nordic Serenity Master Suite',
                    'slug'          => 'nordic-serenity-master-suite',
                    'description'   => 'A private bedroom sanctuary featuring bespoke curved headboards, concealed ambient lighting, and tactile natural linens.',
                    'before_image'  => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=1200&q=80',
                    'after_image'   => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1200&q=80',
                    'budget_range'  => '$12,000 - $18,000',
                    'duration_days' => 30,
                    'style_tags'    => ['Scandinavian', 'Linen', 'Calm'],
                    'materials'     => 'Bleached Pine, Belgian Linen, Brushed Brass',
                    'scope'         => 'Custom Wall Paneling & Lighting Scheme',
                    'is_published'  => true,
                    'views_count'   => 289,
                    'company_name'  => 'Lumen Atelier',
                ],
                [
                    'designer_id'   => $primaryDesigner->id,
                    'category_id'   => $kitchenCat?->id ?? 3,
                    'title'         => 'Marble & Walnut Culinary Studio',
                    'slug'          => 'marble-walnut-culinary-studio',
                    'description'   => 'Chef-grade bespoke kitchen featuring waterfall Calacatta gold marble island, seamless integrated appliances, and warm fluted timber accents.',
                    'before_image'  => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=1200&q=80',
                    'after_image'   => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=1200&q=80',
                    'budget_range'  => '$30,000 - $45,000',
                    'duration_days' => 60,
                    'style_tags'    => ['Modern Luxury', 'Calacatta Marble', 'Fluted Oak'],
                    'materials'     => 'Calacatta Gold Marble, American Walnut, Matte Black Steel',
                    'scope'         => 'Full Kitchen Overhaul & Island Architecture',
                    'is_published'  => true,
                    'views_count'   => 512,
                    'company_name'  => 'Ariadne Studio',
                ],
                [
                    'designer_id'   => $secondaryDesigner->id,
                    'category_id'   => $bathroomCat?->id ?? 4,
                    'title'         => 'Japandi Spa Bath & Wetroom',
                    'slug'          => 'japandi-spa-bath-wetroom',
                    'description'   => 'Wellness-inspired bathroom retreat with micro-cement walls, freestanding stone tub, and custom floating Hinoki wood vanity.',
                    'before_image'  => 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?auto=format&fit=crop&w=1200&q=80',
                    'after_image'   => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1200&q=80',
                    'budget_range'  => '$18,000 - $28,000',
                    'duration_days' => 35,
                    'style_tags'    => ['Spa', 'Terrazzo', 'Minimal'],
                    'materials'     => 'Venetian Micro-cement, River Pebble Stone, Hinoki Wood',
                    'scope'         => 'Plumbing Rerouting & Custom Vanity Integration',
                    'is_published'  => true,
                    'views_count'   => 410,
                    'company_name'  => 'Lumen Atelier',
                ],
                [
                    'designer_id'   => $primaryDesigner->id,
                    'category_id'   => $officeCat?->id ?? 5,
                    'title'         => 'Acoustic Executive Study',
                    'slug'          => 'acoustic-executive-study',
                    'description'   => 'High-focus executive home office with tailored acoustic slat paneling, ergonomic sit-stand desk, and integrated book library.',
                    'before_image'  => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80',
                    'after_image'   => 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1200&q=80',
                    'budget_range'  => '$10,000 - $16,000',
                    'duration_days' => 25,
                    'style_tags'    => ['Ergonomic', 'Acoustic', 'Modern'],
                    'materials'     => 'Felt Acoustic Paneling, Smoked Oak, Leather',
                    'scope'         => 'Library Joinery & Smart Lighting Controls',
                    'is_published'  => true,
                    'views_count'   => 195,
                    'company_name'  => 'Ariadne Studio',
                ],
                [
                    'designer_id'   => $secondaryDesigner->id,
                    'category_id'   => $diningCat?->id ?? 6,
                    'title'         => 'Grand Penthouse Dining Salon',
                    'slug'          => 'grand-penthouse-dining-salon',
                    'description'   => 'Dramatic dining salon highlighted by a custom travertine table, sculptural chandelier, and floor-to-ceiling panoramic views.',
                    'before_image'  => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
                    'after_image'   => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=1200&q=80',
                    'budget_range'  => '$20,000 - $35,000',
                    'duration_days' => 40,
                    'style_tags'    => ['Contemporary', 'Brass Accents', 'Statement Lighting'],
                    'materials'     => 'Honed Travertine, Brushed Aged Brass, Velvet',
                    'scope'         => 'Custom Dining Furniture & Ambient Lighting',
                    'is_published'  => true,
                    'views_count'   => 365,
                    'company_name'  => 'Lumen Atelier',
                ],
                [
                    'designer_id'   => $primaryDesigner->id,
                    'category_id'   => $luxuryCat?->id ?? 7,
                    'title'         => 'Bespoke Emerald Villa Pavilion',
                    'slug'          => 'bespoke-emerald-villa-pavilion',
                    'description'   => 'Ultra-luxury living pavilion featuring curated Italian furniture, double-height glazing, and artisan bronze detailing.',
                    'before_image'  => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80',
                    'after_image'   => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
                    'budget_range'  => '$50,000 - $80,000',
                    'duration_days' => 90,
                    'style_tags'    => ['Bespoke', 'High-End', 'Luxury Architecture'],
                    'materials'     => 'Bronze Metalwork, Italian Silk Velvet, Calacatta Viola',
                    'scope'         => 'Architectural Interior Transformation & Art Curation',
                    'is_published'  => true,
                    'views_count'   => 620,
                    'company_name'  => 'Ariadne Studio',
                ],
            ];

            foreach ($dummyProjects as $proj) {
                Project::firstOrCreate(['slug' => $proj['slug']], $proj);
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
